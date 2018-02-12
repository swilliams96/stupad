<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactPreferencesReferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_prefs', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('flavour');
        });

        $data = array(
            [
                'title' => 'Approval Required',
                'description' => 'Contact details for this listing will only be released to prospective tenants once you approve their contact request.',
                'flavour' => 'Ideal for private property owners.'
            ],
            [
                'title' => 'Instant Release',
                'description' => 'Contact details for this listing will be automatically released to any registered users that request them.',
                'flavour' => 'Ideal for estate agents or institutional proprietors.'
            ],
            [
                'title' => 'Messages Only',
                'description' => 'No contact details are released for this listing - communication with prospective tenants is only made through the messaging system.',
                'flavour' => 'Useful to ensure maximum privacy.'
            ]
        );

        // Seed with default data on creation
        DB::table('contact_prefs')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_prefs');
    }
}
