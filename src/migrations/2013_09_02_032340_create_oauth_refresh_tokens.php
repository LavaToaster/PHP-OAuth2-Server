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
			$table->integer('user_id')->unsigned()->nullable();
			$table->timestamp('expires');
			$table->text('scopes')->nullable();
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