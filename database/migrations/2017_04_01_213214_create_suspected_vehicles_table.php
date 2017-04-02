<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuspectedVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('suspected_vehicles',function(Blueprint $table){
            $table->bigIncrements('id');
            $table->string('vehicle_no')->unique();
            $table->foreign('vehicle_no')
                    ->references('vehicle_no')
                    ->on('vehicles');
            $table->enum('is_active',[0,1])->default(1)->comment('1 for suspected_vehicles. and 0 for unspected vehicles');
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->bigInteger('created_by')->nullable()->unsigned();
            $table->foreign('created_by')
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
        Schema::drop('suspected_vehicles');
    }
}
