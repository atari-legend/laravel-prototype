<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $table = 'individuals';
    protected $primaryKey = 'ind_id';
    public $timestamps = false;

    public function text()
    {
        // FIXME: The DB structure actually allows many
        return $this->hasOne(IndividualText::class, 'ind_id');
    }
}
