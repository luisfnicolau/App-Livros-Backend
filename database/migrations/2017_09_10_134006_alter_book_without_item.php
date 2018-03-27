<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookWithoutItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->boolean('isActive')->default(1);
            $table->integer('buy_quantity')->default(0);
            $table->decimal('buy_price')->nullable();
            $table->string('rent_quantity')->default(0);
            $table->string('rent_price')->nullable();
            $table->string('rent_duration')->nullable();
            $table->string('rent_radius')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->float('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
