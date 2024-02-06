<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserKycsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_kycs', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->index()->nullable();
			$table->string('information')->nullable();
			$table->tinyInteger('status')->default(0)->comment('0=>pending , 1=> verified, 2=>rejected');
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
		Schema::dropIfExists('user_kycs');
	}
}
