<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$json = File::get(database_path('locationJson/cities.json'));
		$cities = json_decode($json, true);

		foreach ($cities as $city) {
			DB::table('cities')->insert($city);
		}
    }
}
