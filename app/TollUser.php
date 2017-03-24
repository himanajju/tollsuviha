<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TollUser extends Model
{
    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function toll(){
        return $this->belongsTo('App\TollDetail','toll_id');
    }
}
