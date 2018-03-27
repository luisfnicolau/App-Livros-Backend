<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBookAlterAddressIdsType extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
   public function up()
   {
     Schema::table('books', function (Blueprint $table) {
         $table->text('address_ids')->nullable(true)->change();
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
         $table->float('address_ids')->nullable(true)->change();
     });
   }
}
