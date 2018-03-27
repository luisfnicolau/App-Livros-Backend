<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAddressesAlterNeighborhoodType extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
   public function up()
   {
     Schema::table('addresses', function (Blueprint $table) {
         $table->text('neighborhood')->nullable(true)->change();
         });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
     Schema::table('addresses', function (Blueprint $table) {
         $table->float('neighborhood')->nullable(true)->change();
     });
   }
}
