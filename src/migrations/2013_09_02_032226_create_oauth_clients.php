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
			$table->string('id', 80)->primary();
			$table->string('secret', 80);
			$table->string('type', 12)->default('public'); // I know the server should not make assumptions on the client type but the majority of clients will be public.
			$table->text('supported_scopes');
			$table->text('redirect_uris');
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