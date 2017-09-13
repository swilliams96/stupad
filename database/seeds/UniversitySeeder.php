<?php

use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
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
            DB::table('universities')->truncate();
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('universities')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Insert the locations data
        DB::table('universities')->insert([
            ['name' => 'University of Bath', 'short_name' => 'Uni of Bath', 'slug' => 'bath-uni', 'area_id' => 1, 'active' => true],
            ['name' => 'Bath Spa University', 'short_name' => 'Bath Spa', 'slug' => 'bath-spa-uni', 'area_id' => 1, 'active' => true],
            ['name' => 'University of Bristol', 'short_name' => 'Uni of Bristol', 'slug' => 'bristol-uni', 'area_id' => 2, 'active' => true],
            ['name' => 'University of West England', 'short_name' => 'UWE', 'slug' => 'uwe', 'area_id' => 2, 'active' => true],
            ['name' => 'University of Exeter', 'short_name' => 'Uni of Exeter', 'slug' => 'exeter-uni', 'area_id' => 3, 'active' => false],
        ]);
    }
}
