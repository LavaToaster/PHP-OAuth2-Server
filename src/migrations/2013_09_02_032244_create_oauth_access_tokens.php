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
			$table->integer('user_id')->unsigned() ->nullable();
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
		Schema::drop('oauth_access_tokens');
	}

}