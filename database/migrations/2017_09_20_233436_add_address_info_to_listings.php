<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressInfoToListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_CONNECTION') == 'sqlite') {
            // Can't add non-nullable column to SQLite database without default value
            Schema::table('listings', function (Blueprint $table) {
                $table->string('address1', 64)->default('');
                $table->string('address2', 64)->nullable();
                $table->string('town', 64)->default('');
                $table->string('postcode', 8)->default('');
            });
        } else {
            Schema::table('listings', function (Blueprint $table) {
                $table->string('address1', 64)->after('pets_allowed');
                $table->string('address2', 64)->nullable()->after('address1');
                $table->string('town', 64)->after('address2');
                $table->string('postcode', 8)->after('town');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listings', function(Blueprint $table) {
            $table->removeColumn('address1');
            $table->removeColumn('address2');
            $table->removeColumn('town');
            $table->removeColumn('postcode');
        });
    }
}
