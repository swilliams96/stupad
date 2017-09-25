<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListingImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listing_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_id')->unsigned();
            $table->integer('image_number')->unsigned();
            $table->string('file_name', 42);    // GUID + "." + 4 character extension (e.g. "jpeg")
            $table->timestamps();

            $table->foreign('listing_id')->references('id')->on('listings');
            $table->unique('file_name');
            $table->unique(['listing_id', 'image_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listing_images');
    }
}
