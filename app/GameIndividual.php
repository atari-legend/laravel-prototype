<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameIndividual extends Model
{
    protected $table = 'game_individual';
    public $timestamps = false;

    public function role()
    {
        return $this->belongsTo(IndividualRole::class, "individual_role_id");
    }

    public function game()
    {
        return $this->belongsTo(Game::class, "game_id");
    }
}
