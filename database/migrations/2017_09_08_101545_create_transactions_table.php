<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->float('book_id');
            $table->boolean('is_buy');
            $table->float('buyer_address');
            $table->float('buyer_id');
//            $table->foreign('buyer_db_id')
//                  ->references('id')
//                  ->on('users')
//                  ->onDelete('cascade');
            $table->string('card_last_digits');
            $table->decimal('paid_value');
            $table->string('payment_method');
            $table->integer('rent_duration')->nullable();
            $table->float('seller_id');
//            $table->foreign('seller_db_id')
//                  ->references('id')
//                  ->on('users')
//                  ->onDelete('cascade');
            $table->string('status');
            $table->timestamp('received_at')->nullable();;
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
        Schema::dropIfExists('transactions');
    }
}
