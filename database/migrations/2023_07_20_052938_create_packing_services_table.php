<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackingServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packing_services', function (Blueprint $table) {
            $table->id();
			$table->foreignId('package_id')->index()->nullable();
			$table->foreignId('variant_id')->index()->nullable();
			$table->double('cost')->nullable();
			$table->double('weight')->nullable();
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
        Schema::dropIfExists('packing_services');
    }
}
