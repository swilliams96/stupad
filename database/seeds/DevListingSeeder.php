<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DevListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset the ID column to start from 1 again...
        if (env('DB_CONNECTION') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');
            DB::table('listings')->truncate();
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('listings')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Insert listings data
        DB::table('listings')->insert([
            [
                'active_datetime' => Carbon::now(),
                'inactive_datetime' => Carbon::now()->addDays(14),
                'title' => 'Sydney Place',
                'landlord_id' => 2,
                'rent_value' => 110,
                'rent_period' => 'week',
                'description' => 'First floor apartment featuring neutral décor and floor coverings. The furnished accommodation includes a spacious sitting room with period fireplace and full height sash windows, kitchen with granite work tops, gas hob/cooker/extractor, fridge/freezer and washing machine, spacious double bedroom with built in storage and recently fitted shower room with waterfall shower. Further benefits such as gas central heating and residents permit parking. Conveniently located within a level walk of the city centre. Would ideally suit a single professional applicant or couple, with post graduates and mature students considered.',
                'short_description' => 'First floor apartment featuring neutral décor and floor coverings. The furnished accommodation includes a spacious sitting room with period fireplace and full height sash windows',
                'area_id' => 1,
                'lat' => 51.386156062079,
                'lng' => -2.35189667259341,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'town_distance' => 8,
                'furnished' => true,
                'bills_included' => true,
                'pets_allowed' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
