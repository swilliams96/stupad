<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table)  {
            $table->integer('header_image')->unsigned()->after('postcode');

            $table->integer('town_distance')->unsigned()->nullable()->change();
            $table->integer('area_id')->unsigned()->nullable()->change();
            $table->decimal('lat', 10, 8)->nullable()->change();
            $table->decimal('lng', 11, 8)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('header_image');

            $table->integer('town_distance')->unsigned(false)->nullable(false)->change();
            $table->integer('area_id')->unsigned(false)->nullable(false)->change();
            $table->decimal('lat', 10, 8)->nullable(false)->change();
            $table->decimal('lng', 11, 8)->nullable(false)->change();
        });
    }
}
