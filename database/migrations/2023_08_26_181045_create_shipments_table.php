<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
			$table->id();
			$table->integer('shipment_identifier')->nullable()->comment('1=> operator_country, 2=> internationally');
			$table->string('shipment_type')->nullable();
			$table->string('shipment_id')->nullable();
			$table->double('receive_amount')->nullable();
			$table->timestamp('shipment_date')->nullable();
			$table->timestamp('delivery_date')->nullable();
			$table->foreignId('sender_branch')->index()->nullable();
			$table->foreignId('receiver_branch')->index()->nullable();
			$table->foreignId('sender_id')->index()->nullable();
			$table->foreignId('receiver_id')->index()->nullable();
			$table->foreignId('from_country_id')->index()->nullable();
			$table->foreignId('from_state_id')->index()->nullable();
			$table->foreignId('from_city_id')->index()->nullable();
			$table->foreignId('from_area_id')->index()->nullable();
			$table->foreignId('to_country_id')->index()->nullable();
			$table->foreignId('to_state_id')->index()->nullable();
			$table->foreignId('to_city_id')->index()->nullable();
			$table->foreignId('to_area_id')->index()->nullable();
			$table->integer('payment_by')->nullable();
			$table->string('payment_type')->nullable();
			$table->integer('payment_status')->nullable();
			$table->json('packing_services')->nullable();
			$table->json('parcel_information')->nullable();
			$table->longText('parcel_details')->nullable();
			$table->double('discount')->nullable();
			$table->double('discount_amount')->nullable();
			$table->double('sub_total')->nullable();
			$table->double('pickup_cost')->nullable();
			$table->double('supply_cost')->nullable();
			$table->double('shipping_cost')->nullable();
			$table->double('tax')->nullable();
			$table->double('insurance')->nullable();
			$table->double('total_pay')->nullable();
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
        Schema::dropIfExists('shipments');
    }
}
