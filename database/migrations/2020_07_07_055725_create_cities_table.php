<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create(config('world.migrations.cities.table_name'), function (Blueprint $table) {
			$table->id();
			$table->foreignId('country_id')
				->constrained('countries') // Assuming your countries table is named 'countries'
				->onDelete('cascade'); // Cascade delete when a country is deleted
			$table->foreignId('state_id')
				->constrained('states') // Assuming your states table is named 'states'
				->onDelete('cascade'); // Cascade delete when a state is deleted
			$table->string('name');
			$table->boolean('status')->default(true);
			$table->timestamps();

			foreach (config('world.migrations.cities.optional_fields') as $field => $value) {
				if ($value['required']) {
					$table->string($field, $value['length'] ?? null);
				}
			}
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists(config('world.migrations.cities.table_name'));
	}
}
