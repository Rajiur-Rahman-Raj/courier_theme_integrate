<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKYCSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('k_y_c_s', function (Blueprint $table) {
            $table->id();
			$table->foreignId('user_id')->index()->nullable();
			$table->foreignId('admin_id')->index()->nullable();
			$table->string('kyc_type')->nullable();
			$table->text('details')->nullable();
			$table->boolean('status')->default(0)->comment('1=> Approved, 2 => Reject');
			$table->text('feedback')->nullable();
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
        Schema::dropIfExists('k_y_c_s');
    }
}
