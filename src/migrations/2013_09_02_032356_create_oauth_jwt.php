<?php

use Illuminate\Database\Migrations\Migration;

class CreateOauthJwt extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_jwt', function(\Illuminate\Database\Schema\Blueprint $table)
		{
			$table->string('public_key')->primary();
			$table->string('client_id', 80);
			$table->string('subject');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth_jwt');
	}

}