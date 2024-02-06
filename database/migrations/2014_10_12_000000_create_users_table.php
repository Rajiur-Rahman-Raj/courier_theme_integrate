<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->foreignId('ref_by')->nullable()->constrained('users');
			$table->string('name');
			$table->string('username', 50)->unique()->nullable();
			$table->string('email', 50)->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->boolean('status')->default(1)->comment('0 = inactive, 1 = active');
			$table->decimal('balance', 11,2)->default(0);
			$table->bigInteger('language_id')->nullable();
			$table->boolean('email_verification')->default(0)->comment('0 = inactive, 1 = active');
			$table->boolean('sms_verification')->default(0)->comment('0 = inactive, 1 = active');
			$table->string('verify_code',10)->nullable();
			$table->dateTime('sent_at')->nullable();
			$table->boolean('two_fa')->default(0)->comment('0 => two-Fa off, 1 => two-fa on');
			$table->boolean('two_fa_verify')->default(1)->comment('0: two-FA unverified, 1: two-FA verified');
			$table->string('two_fa_code')->nullable();
			$table->string('password');
			$table->string('remember_token')->nullable();
			$table->boolean('identity_verify')->nullable()->comment('0 => Not Applied, 1=> Applied, 2=> Approved, 3 => Rejected	');
			$table->timestamp('last_login')->nullable();
			$table->integer('user_type')->default(0)->nullable()->comment('0=> normal user, 1=> sender/customer 2=> receiver');
			$table->rememberToken();
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
		Schema::dropIfExists('users');
	}
}
