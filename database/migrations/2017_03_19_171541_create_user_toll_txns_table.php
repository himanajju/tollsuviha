<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTollTxnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_toll_txns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
            $table->string('vehicle_no');
            $table->enum('vehicle_type',['car_jeep_van','lcv','Bus_truck','axle_upto_3','axle_4_to_6','axle_7_or_more','hcm_eme']);
            $table->decimal('toll_amount',5,2)->default('0.00');
            $table->bigInteger('toll_id')->unsigned();
            $table->foreign('toll_id')
                  ->references('id')
                  ->on('toll_details');
            $table->string('wallet_id');
            $table->decimal('amount',4,2)->default('0.00');
            $table->string('txn_id')->unique();
            $table->string('json_data');
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
        Schema::drop('user_toll_txns');
    }
}
