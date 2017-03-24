<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //Usergroup Relation
    public function usergroup()
    {
        return $this->belongsTo('App\Usergroup','usergroup_id');
    }

    public function user()
    {
        return $this->hasMany('App\TollUser','user_id');
    }
}
