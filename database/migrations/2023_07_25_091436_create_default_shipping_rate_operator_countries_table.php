<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultShippingRateOperatorCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_shipping_rate_operator_countries', function (Blueprint $table) {
            $table->id();
			$table->foreignId('country_id')->index()->nullable();
			$table->foreignId('shipping_date_id')->index()->nullable();
			$table->double('pickup_cost')->default(0.00)->nullable();
			$table->double('supply_cost')->default(0.00)->nullable();
			$table->double('shipping_cost')->default(0.00)->nullable();
			$table->double('return_shipping_cost')->default(0.00)->nullable();
			$table->double('default_tax')->default(0.00)->nullable();
			$table->double('default_insurance')->default(0.00)->nullable();
			$table->boolean('status')->default(1);
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
        Schema::dropIfExists('default_shipping_rate_operator_countries');
    }
}
