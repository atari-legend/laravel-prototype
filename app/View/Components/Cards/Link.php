<?php

namespace App\View\Components\Cards;

use App\Models\Website;
use Illuminate\View\Component;

class Link extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        $website = null;

        Website::all()
            ->whenNotEmpty(function ($collection) use (&$website) {
                $website = $collection->random();
            });

        return view('components.cards.link')
            ->with(['website' => $website]);
    }
}
