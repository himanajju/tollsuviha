<?php

use Illuminate\Database\Seeder;

class VehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vehicles')->insert(['id' => '1',
                                    'vehicle_no' => 'CG04LB2955',
                                    'vehicle_type' => 'car_jeep_van',
                                    'created_at' => '2016-11-15 00:00:00',
                                    'updated_at' => '2016-11-15 00:00:00']);
		DB::table('vehicles')->insert(['id' => '2',
		                            'vehicle_no' => 'CG04LB2956',
		                            'vehicle_type' => 'bus_truck',
		                            'created_at' => '2016-11-15 00:00:00',
		                            'updated_at' => '2016-11-15 00:00:00']);
		DB::table('vehicles')->insert(['id' => '3',
		                            'vehicle_no' => 'CG04LB2957',
		                            'vehicle_type' => 'lcv',
		                            'created_at' => '2016-11-15 00:00:00',
		                            'updated_at' => '2016-11-15 00:00:00']);
		DB::table('vehicles')->insert(['id' => '4',
		                            'vehicle_no' => 'CG04LB2958',
		                            'vehicle_type' => 'upto_3_axle_vehicle',
		                            'created_at' => '2016-11-15 00:00:00',
		                            'updated_at' => '2016-11-15 00:00:00']);

    }
}
