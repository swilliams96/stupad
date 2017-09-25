<?php

use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
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
            DB::table('areas')->truncate();
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('areas')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        // Insert the locations data
        DB::table('areas')->insert([
            ['name' => 'Bath', 'suffix' => 'town', 'active' => true, 'admin_district' => 'Bath and North East Somerset', 'postcode_list' => 'BA,SN12,SN13'],
            ['name' => 'Bristol', 'suffix' => 'city', 'active' => true, 'admin_district' => 'Bristol, City of', 'postcode_list' => 'BS'],
            ['name' => 'Exeter', 'suffix' => 'town', 'active' => false, 'admin_district' => 'Exeter', 'postcode_list' => ''],
        ]);
    }
}
