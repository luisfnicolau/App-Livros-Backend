<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('isActive')->default(1);
            $table->integer('buy_quantity')->default(0);
            $table->decimal('buy_price')->nullable();
            $table->string('rent_quantity')->default(0);
            $table->string('rent_price')->nullable();
            $table->string('rent_duration')->nullable();
            $table->string('rent_radius')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->float('book_id');
            $table->float('seller_id');
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
        Schema::dropIfExists('item');
    }
}
