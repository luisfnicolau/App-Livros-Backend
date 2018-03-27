<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionTableAlterCardLastDigitsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('transactions', function (Blueprint $table) {
          $table->string('card_last_digits')->nullable(true)->change();
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('transactions', function (Blueprint $table) {
          $table->string('card_last_digits')->nullable(false)->change();
          });
    }
}
