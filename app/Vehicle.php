<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function suspectedVehicle()
    {
    	return $this->hasOne('App\SuspectedVehicle','vehicle_no');
    }
}
