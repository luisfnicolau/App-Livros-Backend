<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('messages', function (Blueprint $table) {
           // $table->dropColumn('receiver_id');
           // $table->dropColumn('book_id');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
          // $table->float('receiver_id');
		  // $table->float('book_id');
          });
    }
}
