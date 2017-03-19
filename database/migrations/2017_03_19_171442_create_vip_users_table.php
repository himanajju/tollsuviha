<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVipUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip_users', function (Blueprint $table) {
            $table->BigIncrements('id');
            $table->string('designation')->comment('Designation of VIP');
            $table->string('vehicle_no');
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
        Schema::drop('vip_users');
    }
}
