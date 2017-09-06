<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DevLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset the ID column to start from 1 again...
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('locations')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Insert the locations data
        DB::table('locations')->insert([
            ['name' => 'Bath Town', 'short_name' => 'Bath', 'slug' => 'bath-town', 'area_id' => 1, 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'University of Bath', 'short_name' => 'Uni of Bath', 'slug' => 'bath', 'area_id' => 1, 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bath Spa University', 'short_name' => 'Bath Spa', 'slug' => 'bathspa', 'area_id' => 1, 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'University of Bristol', 'short_name' => 'Uni of Bristol', 'slug' => 'bristol', 'area_id' => 2, 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'University of West England', 'short_name' => 'UWE', 'slug' => 'uwe', 'area_id' => 2, 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Bristol Town', 'short_name' => 'Bristol', 'slug' => 'bristol-town', 'area_id' => 2, 'active' => true, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ]);
    }
}
