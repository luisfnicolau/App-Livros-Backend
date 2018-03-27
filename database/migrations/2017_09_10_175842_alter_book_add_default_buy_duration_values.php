<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBookAddDefaultBuyDurationValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('rent_price')->default(0)->change();
            $table->string('rent_duration')->default(0)->change();
            $table->string('rent_radius')->default(0)->change();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('rent_price')->nullable();
            $table->string('rent_duration')->nullable();
            $table->string('rent_radius')->nullable();
            });
    }
}
