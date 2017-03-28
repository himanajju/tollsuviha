<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) { 
            $table->bigIncrements('id');
            $table->string('name')->comment('Full name of users');
            $table->string('email')->unique()->nullable();
            $table->string('password')->comment('login password');
            $table->string('contact_no');
            $table->string('wallet_id')->unique()->nullable();
            $table->decimal('wallet_amt',15,2)->default('0.00')->comment('balance amount in wallet');
            $table->bigInteger('usergroup_id')->unsigned();
            $table->enum('is_active',['0','1'])->comment('1 for active users');
            $table->enum('is_blocked',['0','1'])->default('0')->comment('1 for blocked users');
            $table->foreign('usergroup_id')
                  ->references('id')
                  ->on('usergroups');
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
        Schema::drop('users');
    }
}
