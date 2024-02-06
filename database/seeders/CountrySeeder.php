<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$json = File::get(database_path('locationJson/countries.json'));
		$countries = json_decode($json, true);

		foreach ($countries as $countryData) {
			$timezones = json_encode($countryData['timezones']);
			$translations = json_encode($countryData['translations']);

			DB::table('countries')->insert([
				'id' => $countryData['id'],
				'name' => $countryData['name'],
				'iso3' => $countryData['iso3'],
				'iso2' => $countryData['iso2'],
				'numeric_code' => $countryData['numeric_code'],
				'phonecode' => $countryData['phone_code'],
				'capital' => $countryData['capital'],
				'currency' => $countryData['currency'],
				'currency_name' => $countryData['currency_name'],
				'currency_symbol' => $countryData['currency_symbol'],
				'tld' => $countryData['tld'],
				'native' => $countryData['native'],
				'region' => $countryData['region'],
				'subregion' => $countryData['subregion'],
				'timezones' => $timezones,
				'translations' => $translations,
				'latitude' => $countryData['latitude'],
				'longitude' => $countryData['longitude'],
				'emoji' => $countryData['emoji'],
				'emojiU' => $countryData['emojiU'],
			]);
		}
    }
}
