<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingRateInternationalliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_rate_internationallies', function (Blueprint $table) {
            $table->id();
			$table->foreignId('from_country_id')->index()->nullable();
			$table->foreignId('to_country_id')->index()->nullable();
			$table->foreignId('from_state_id')->index()->nullable();
			$table->foreignId('to_state_id')->index()->nullable();
			$table->foreignId('from_city_id')->index()->nullable();
			$table->foreignId('to_city_id')->index()->nullable();
			$table->foreignId('parcel_type_id')->index()->nullable();
			$table->double('shipping_cost')->default(0.00)->nullable();
			$table->double('return_shipment_cost')->default(0.00)->nullable();
			$table->double('tax')->default(0.00)->nullable();
			$table->double('insurance')->default(0.00)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_rate_internationallies');
    }
}
