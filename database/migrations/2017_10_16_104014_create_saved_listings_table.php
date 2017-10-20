<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user')->unsigned();
            $table->integer('listing')->unsigned();
            $table->dateTime('saved_datetime')->nullable();
            $table->dateTime('unsaved_datetime')->nullable();

            $table->foreign('user')->references('id')->on('users');
            $table->foreign('listing')->references('id')->on('listings');
            $table->unique(['user', 'listing']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_listings');
    }
}
