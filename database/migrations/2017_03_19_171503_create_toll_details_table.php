<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTollDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toll_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('toll_id')->unique()->comment('refereence id of toll');
            $table->string('toll_name')->comment('name of toll plaza');
            $table->string('city')->comment('location of toll plaza');
            $table->string('state')->comment('toll plaza region');
            $table->decimal('car_jeep_van_price',4,2)->default('0.00')->comment('car/jeep/van price');
            $table->decimal('lcv_price',4,2)->default('0.00')->comment('LVC price');
            $table->decimal('bus_truck_price',4,2)->default('0.00')->comment('bus/truck price');
            $table->decimal('upto_3_axle_vehicle_price',4,2)->default('0.00')->comment('upto 3 axle vehicle price');
            $table->decimal('axle_4_to_6_vehicle_price',4,2)->default('0.00')->comment('4 to 6 axle vehicle price');
            $table->decimal('axle_7_or_more_vehicle_price',4,2)->default('0.00')->comment('7 or more axle vechile price');
            $table->decimal('hcm_eme_price',4,2)->default('0.00')->comment('HCM/EME price');
            $table->enum('highway',['0','1'])->comment('states the type of toll plaza, 0 for National Highway');
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')
                  ->references('id')
                  ->on('users');
            $table->bigInteger('update_by')->nullable()->unsigned();
            $table->foreign('update_by')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('toll_details');
    }
}
