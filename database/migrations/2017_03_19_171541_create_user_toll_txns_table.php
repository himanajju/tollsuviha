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
            $table->bigInteger('wallet_id')->unsigned();
            $table->foreign('wallet_id')
                  ->references('id')
                  ->on('user_wallets');
            $table->decimal('amount',4,2)->default('0.00');
            $table->string('txn_id')->unique();
            $table->string('json_data');
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
        Schema::drop('user_toll_txns');
    }
}
