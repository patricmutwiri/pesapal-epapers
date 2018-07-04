<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid')->unsigned();
            $table->integer('total')->unsigned();
            $table->string('papers');
            $table->string('businessid');
            $table->string('transactionid');
            $table->string('trackingid');
            $table->string('payment_method');
            $table->string('payment_status');
            $table->string('status');
            $table->string('description');
            $table->string('dropoff');
            $table->string('phonenumber');
            $table->string('amount');
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
        Schema::dropIfExists('orders');
    }
}
