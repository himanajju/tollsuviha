<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    public function user()
    {
        return $this->belongsTo('App\UserDevice','user_id');
    }

}
