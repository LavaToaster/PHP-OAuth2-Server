<?php

use Illuminate\Database\Migrations\Migration;

class CreateOauthAccessTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_access_tokens', function(\Illuminate\Database\Schema\Blueprint $table)
		{
			$table->string('access_token', 40)->primary();
			$table->string('client_id', 80);
			$table->string('user_id')->nullable(); // Need to come back to this
			$table->timestamp('expires');
			$table->text('scope')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth_access_tokens');
	}

}