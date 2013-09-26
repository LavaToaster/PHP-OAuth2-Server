<?php

use Illuminate\Database\Migrations\Migration;

class CreateOauthAuthorizationCodes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_authorization_codes', function(\Illuminate\Database\Schema\Blueprint $table)
		{
			$table->string('authorization_code', 40)->primary();
			$table->string('client_id', 80);
			$table->integer('user_id')->unsigned()->nullable();
			$table->text('redirect_uri')->nullable();
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
		Schema::drop('oauth_authorization_codes');
	}

}