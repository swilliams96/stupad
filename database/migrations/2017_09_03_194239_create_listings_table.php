<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('active_datetime')->nullable();
            $table->dateTime('inactive_datetime')->nullable();
            $table->string('title');
            $table->integer('landlord_id')->unsigned();
            $table->integer('rent_value');
            $table->string('rent_period');
            $table->text('description');
            $table->text('short_description');
            $table->integer('area_id')->unsigned();
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);
            $table->integer('bedrooms')->unsigned();
            $table->integer('bathrooms')->unsigned();
            $table->integer('town_distance');
            $table->boolean('furnished')->default(false);
            $table->boolean('bills_included')->default(false);
            $table->boolean('pets_allowed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listings');
    }
}
