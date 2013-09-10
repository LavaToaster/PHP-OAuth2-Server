<?php

use Illuminate\Database\Migrations\Migration;

class CreateOauthRefreshTokens extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_refresh_tokens', function(\Illuminate\Database\Schema\Blueprint $table)
		{
			$table->string('refresh_token', 40)->primary();
			$table->string('client_id', 80);
			$table->string('user_id'); // Need to come back to this
			$table->timestamp('expires');
			$table->text('scope');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth_refresh_tokens');
	}

}