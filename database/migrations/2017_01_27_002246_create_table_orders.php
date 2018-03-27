<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrders extends Migration
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
            $table->decimal('total', 40, 4)->unsigned();
            $table->integer('renter_id')->unsigned();
            $table->integer('owner_id')->unsigned();
            $table->tinyInteger('rating')->unsigned()->nullable();

            $table->foreign('renter_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('owner_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
           
            $table->timestamps();
            $table->softDeletes();
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
