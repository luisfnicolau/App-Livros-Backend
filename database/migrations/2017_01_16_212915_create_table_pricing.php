<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePricing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricings', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('max_books')->default(50);
            $table->integer('price')->default(0); // price in cents, to avoid truncating values
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
        Schema::dropIfExists('pricings');
    }
}
