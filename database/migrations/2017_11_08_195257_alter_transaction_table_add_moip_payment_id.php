<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransactionTableAddMoipPaymentId extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('transactions', function (Blueprint $table) {
        $table->text('moip_payment_id')->nullable();
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
        $table->dropColumn('moip_payment_id');
    });
  }
}
