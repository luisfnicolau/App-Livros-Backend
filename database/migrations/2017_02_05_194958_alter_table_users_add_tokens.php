<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableUsersAddTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_token', 512)->nullable();
            $table->string('google_token', 512)->nullable();
            $table->string('app_token', 512)->nullable();
            $table->datetime('token_expires')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if(Schema::hasColumn('users', 'facebook_token'))
                $table->dropColumn('facebook_token');
            if(Schema::hasColumn('users', 'google_token'))
                $table->dropColumn('google_token');
            if(Schema::hasColumn('users', 'app_token'))
                $table->dropColumn('app_token');
            if(Schema::hasColumn('users', 'token_expires'))
                $table->dropColumn('token_expires');
        });
    }
}
