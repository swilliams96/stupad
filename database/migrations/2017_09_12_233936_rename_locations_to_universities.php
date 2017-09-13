<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLocationsToUniversities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('locations')) {
            Schema::rename('locations', 'universities');
        }
        Schema::table('universities', function (Blueprint $table) {
            $table->foreign('area_id')->references('id')->on('areas');
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('universities', function (Blueprint $table) {
            $table->dropForeign('area_id')->references('id')->on('areas');
            $table->timestamps();
        });
        if (Schema::hasTable('universities')) {
            Schema::rename('universities', 'locations');
        }
    }
}
