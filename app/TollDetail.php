<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TollDetail extends Model
{
    public function toll()
    {
        return $this->hasMany('App\TollUser','toll_id');
    }
}
