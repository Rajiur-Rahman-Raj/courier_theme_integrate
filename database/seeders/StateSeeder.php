<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$json = File::get(database_path('locationJson/states.json'));
		$states = json_decode($json, true);

		foreach ($states as $state) {
			DB::table('states')->insert($state);
		}
    }
}
