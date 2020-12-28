<?php

namespace App\Console\Commands;

use App\Helpers\ChangelogHelper;
use App\Models\Changelog;
use App\Models\Crew;
use App\Models\Menu;
use App\Models\MenuDisk;
use App\Models\MenuDiskCondition;
use App\Models\MenuDiskDump;
use App\Models\MenuDiskScreenshot;
use App\Models\MenuSet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ImportStonishData extends Command
{

    private $stop = false;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stonish:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Stonish menus data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        pcntl_signal(SIGINT, [$this, 'interrupt']);

        DB::table('menu_disk_dumps')->delete();
        DB::table('menu_disk_screenshots')->delete();
        DB::table('menu_disks')->delete();
        DB::table('crew_menu_set')->delete();
        DB::table('menus')->delete();
        DB::table('menu_sets')->delete();

        foreach (Storage::disk('public')->files('images/menu_screenshots/') as $file) {
            Storage::disk('public')->delete($file);
        }
        foreach (Storage::disk('public')->files('zips/menus/') as $file) {
            Storage::disk('public')->delete($file);
        }

        $db = DB::connection('stonish');

        $menus = $db->table('allmenus')
            ->join('namemenus', 'name', '=', 'namemenus.id_namemenus')
            ->select('allmenus.*', 'namemenus.name_menus')
            ->orderBy('name_menus')
            ->orderBy('issue')
            ->orderBy('letter')
            ->orderBy('version')
            ->get();

        foreach ($menus as $stonishMenu) {
            if ($this->stop) {
                break;
            }

            $this->info("{$stonishMenu->name_menus} #{$stonishMenu->issue} {$stonishMenu->letter} {$stonishMenu->version}");

            $crew = $this->getCrew($stonishMenu);
            $menuset = $this->getMenuSet($crew, $stonishMenu);
            $menu = $this->getMenu($menuset, $stonishMenu);
            $disk = $this->getMenuDisk($menu, $stonishMenu);

            if ($stonishMenu->download !== null && trim($stonishMenu->download) !== '') {
                $dumpFile = env('STONISH_ROOT')
                    . 'download/' . preg_replace('/\s/', '_', $stonishMenu->name_menus)
                    . '/' . $stonishMenu->download;
                if (file_exists($dumpFile)) {
                    $this->info("\t\tFound dump {$dumpFile}");

                    $dump = new MenuDiskDump();
                    $dump->user_id = 0; // FIXME
                    $dump->menu_disk_condition_id = $stonishMenu->stateofdisk;

                    $zip = new ZipArchive();
                    if ($zip->open($dumpFile) === true) {
                        if ($zip->count() !== 1) {
                            $this->error("Unexpected number of files found in ZIP archive {$dumpFile}: {$zip->count()}");
                            exit(1);
                        }

                        $filename = $zip->getNameIndex(0);
                        $ext = strtoupper(pathinfo($filename, PATHINFO_EXTENSION));
                        $dump->format = $ext;

                        $content = $zip->getFromIndex(0);
                        $dump->sha512 = hash('sha512', $content);
                        $dump->size = strlen($content);
                    } else {
                        $this->error("Error opening ZIP file {$dumpFile}");
                        exit(1);
                    }

                    $disk->dumps()->save($dump);
                    Storage::disk('public')->put('zips/menus/' . $dump->id . '.zip', file_get_contents($dumpFile));
                }
            }


            $this->info("\t\tImported menu!");
        }
        return 0;
    }

    private function getCrew(object $menu): Crew
    {
        $crews = Crew::whereRaw('LOWER(TRIM(crew_name)) = ?', strtolower(trim($menu->name_menus)));
        $crew = null;
        if ($crews->count() === 1) {
            $crew = $crews->first();
            $this->info("\tFound crew {$crew->crew_id} {$crew->crew_name}");
        } else if ($crews->count() < 1) {
            $this->warn("\tNo crew found with name {$menu->name_menus}. Creating a new one");
            $crew = Crew::create([
                'crew_name' => $menu->name_menus
            ]);
            ChangelogHelper::insert([
                'action'           => Changelog::INSERT,
                'section'          => 'Crew',
                'section_id'       => $crew->getKey(),
                'section_name'     => $crew->crew_name,
                'sub_section'      => 'Crew',
                'sub_section_id'   => $crew->getKey(),
                'sub_section_name' => $crew->crew_name,
            ]);
        } else if ($crews->count() > 1) {
            $this->error("\tMore than 1 crew found for {$menu->name_menus}");
            $this->error("\t" . $crews->pluck('crew_id')->join(', '));
            exit(1);
        }

        return $crew;
    }

    private function getMenuSet(Crew $crew, object $menu): MenuSet
    {
        $menusets = MenuSet::whereRaw('LOWER(TRIM(name)) = ?', strtolower(trim($menu->name_menus)));
        $menuset = null;
        if ($menusets->count() === 1) {
            $menuset = $menusets->first();
            $this->info("\tFound menu set {$menuset->id} {$menuset->name}");
        } else if ($menusets->count() < 1) {
            $this->warn("\tNo menu set found with name {$menu->name_menus}. Creating a new one");
            $menuset = new MenuSet();
            $menuset->name = $menu->name_menus;
            $crew->menuSets()->save($menuset);
        } else if ($menusets->count() > 2) {
            $this->error("\tMore than 1 menu set found for {$menu->name_menus}");
            $this->error("\t" . $menusets->pluck('id')->join(', '));
            exit(1);
        }

        return $menuset;
    }

    private function getMenu(MenuSet $menuset, object $stonishMenu): Menu
    {
        $menus = Menu::where('menu_set_id', $menuset->id);

        if ($stonishMenu->issue !== null) {
            $menus = $menus->where('number', $stonishMenu->issue);
        } else if ($stonishMenu->letter !== null && trim($stonishMenu->letter) !== '') {
            $menus = $menus->where('issue', trim($stonishMenu->letter));
        } else {
            $menus = $menus->whereNull('number');
        }

        $menus = $menus->where('version', (trim($stonishMenu->version) !== '') ? trim($stonishMenu->version) : null);
        $menu = null;
        if ($menus->count() === 1) {
            $menu = $menus->first();
            $this->info("\t\tFound menu {$menu->id} {$menu->issue}");
        } else if ($menus->count() < 1) {
            $this->warn("\t\tNo menu found for {$stonishMenu->issue}. Creating a new one");
            $menu = new Menu();
            $menu->number = $stonishMenu->issue;
            if ($stonishMenu->issue === null && $stonishMenu->letter !== null && trim($stonishMenu->letter) !== '') {
                $menu->issue = $stonishMenu->letter;
            }
            $menu->date = ($stonishMenu->date_release !== '0000-00-00') ? $stonishMenu->date_release : null;
            $menu->version = (trim($stonishMenu->version) !== '') ? trim($stonishMenu->version) : null;
            $menuset->menus()->save($menu);
        } else if ($menus->count() > 1) {
            $this->error("\t\tMore than 1 menu found for {$stonishMenu->id_allmenus}");
            $this->error("\t\t" . $menus->pluck('id')->join(', '));
            exit(1);
        }

        return $menu;
    }

    private function getMenuDisk(Menu $menu, object $stonishMenu): MenuDisk
    {
        $disk = new MenuDisk();
        $disk->part = ($stonishMenu->letter !== null && trim($stonishMenu->letter) !== '') ? trim($stonishMenu->letter) : null;

        // Read scrolltext from disk if it exists
        if ($stonishMenu->scrolltext !== null && trim($stonishMenu->scrolltext) !== '') {
            $scrolltextFile = env('STONISH_ROOT') . 'scrolltext/' . $stonishMenu->scrolltext;
            if (file_exists($scrolltextFile)) {
                $scrolltext = file_get_contents($scrolltextFile);
                // Strip off non-printable characters
                $disk->scrolltext = preg_replace('/[[:^print:]]/', '', $scrolltext);
            }
        }
        $disk->notes = $stonishMenu->comments;
        $menu->disks()->save($disk);

        if ($stonishMenu->screenshot !== null && trim($stonishMenu->screenshot) !== '') {
            $screenshotFile = env('STONISH_ROOT') . 'screenshot/' . $stonishMenu->screenshot;
            if (file_exists($screenshotFile)) {
                $ext = strtolower(pathinfo($screenshotFile, PATHINFO_EXTENSION));
                $screenshot = new MenuDiskScreenshot();
                $screenshot->imgext = $ext;
                $disk->screenshots()->save($screenshot);

                Storage::disk('public')->put('images/menu_screenshots/' . $screenshot->id . '.' . $ext, file_get_contents($screenshotFile));
            }
        }

        return $disk;
    }

    public function interrupt()
    {
        $this->info("Interrupting...");
        $this->stop = true;
    }
}