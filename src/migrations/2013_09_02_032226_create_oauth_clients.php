<?php

use Illuminate\Database\Migrations\Migration;

class CreateOauthClients extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oauth_clients', function(\Illuminate\Database\Schema\Blueprint $table)
		{
			$table->string('client_id', 80)->primary();
			$table->string('client_secret', 80);
			$table->string('default_scope');
			$table->text('supported_scopes');
			$table->text('redirect_uri');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('oauth_clients');
	}

}