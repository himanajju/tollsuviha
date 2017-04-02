<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertTollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alert_tolls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('toll_id')->unsigned();
            $table->foreign('toll_id')
                  ->references('id')
                  ->on('toll_details');
            $table->bigInteger('alert_id')->unsigned();
            // $table->foreign('alert_id')
            //       ->references('id')
            //       ->on('alert');           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alert_tolls');
    }
}
