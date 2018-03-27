<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionTableAlterPaidValueVarchar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('transactions', function (Blueprint $table) {
          $table->string('paid_value')->change();
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
          $table->decimal('paid_value')->change();
      });
    }
}
