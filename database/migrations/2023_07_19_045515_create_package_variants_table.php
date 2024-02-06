<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_variants', function (Blueprint $table) {
            $table->id();
			$table->foreignId('package_id')->index()->nullable();
			$table->string('variant')->nullable();
			$table->string('image')->nullable();
			$table->string('driver')->nullable();
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
        Schema::dropIfExists('package_variants');
    }
}
