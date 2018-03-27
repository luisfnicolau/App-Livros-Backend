<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBookCopies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_copies', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message')->nullable();
            $table->string('photo')->nullable();
            $table->decimal('price', 19, 4)->unsigned();
            $table->integer('book_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('renter_id')->unsigned()->nullable();

            $table->foreign('book_id')
                    ->references('id')
                    ->on('books');

            $table->foreign('user_id')
                    ->references('id')
                    ->on('users');

            $table->foreign('renter_id')
                    ->references('id')
                    ->on('users');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_copies');
    }
}
