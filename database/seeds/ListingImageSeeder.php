<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ListingImageSeeder extends Seeder
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
            DB::table('listing_images')->truncate();
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('listing_images')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Insert listings data
        DB::table('listing_images')->insert([
            [
                'listing_id' => 1,
                'image_number' => 1,
                'file_name' => '755c8dc7-4a87-4112-a8f9-73f3681d08b9.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 2,
                'file_name' => '343f125f-55b5-47b7-be26-448c27a0016b.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 3,
                'file_name' => 'a221cd03-b515-4fbb-a5e7-12e35c284286.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 4,
                'file_name' => '491d0089-8e44-4f49-8487-553a3ea3be82.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 5,
                'file_name' => 'ab9176cd-d13b-46e8-ae7a-362b23ee81b6.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 6,
                'file_name' => 'ba621748-4004-42de-95e9-f78cba0688fc.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 7,
                'file_name' => 'c765e865-7f88-4853-a000-95bebbb083fd.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'listing_id' => 1,
                'image_number' => 8,
                'file_name' => 'd02aad24-be9a-4c7b-9867-c7d009570e5d.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
