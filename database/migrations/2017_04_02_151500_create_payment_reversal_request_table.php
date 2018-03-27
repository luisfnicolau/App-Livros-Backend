<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReversalRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('payment_reversal_requests', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->integer('order_id')->unsigned()->nullable();
        $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        $table->boolean('fulfilled');
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
      Schema::dropIfExists('payment_reversal_requests');
    }
}
