<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableOrdersAddLatitudeAndLongitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('orders', function (Blueprint $table) {
        $table->float('latitude', 6, 4)->nullable();
        $table->float('longitude', 6, 4)->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('orders', function (Blueprint $table) {
        if(Schema::hasColumn('orders', 'latitude'))
          $table->dropColumn('latitude');
        if(Schema::hasColumn('orders', 'longitude'))
          $table->dropColumn('longitude');
      });
    }
}
