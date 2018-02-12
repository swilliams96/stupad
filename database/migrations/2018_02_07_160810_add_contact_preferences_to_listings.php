<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactPreferencesToListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->integer('contact_prefs')->default(1)->after('header_image')->references('id')->on('contact_prefs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('listings', 'contact_prefs')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn('contact_prefs');
            });
        } else {
            $this->info('** No contact_prefs column found in listings table! **');
        }
    }
}
