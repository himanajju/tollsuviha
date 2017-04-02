<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuspectedVehicle extends Model
{
    public function suspectedVehicle()
    {
    	return $this->belongsTo('App\Vehicle','vehicle_no');
    }
}
