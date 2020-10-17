<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoxScan extends Model
{
    protected $table = 'game_boxscan';
    protected $primaryKey = 'game_boxscan_id';
    public $timestamps = false;

    public function getFileAttribute()
    {
        return $this->game_boxscan_id
            .'.'
            .$this->imgext;
    }
}