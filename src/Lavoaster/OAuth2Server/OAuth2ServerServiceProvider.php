<?php namespace Lavoaster\OAuth2Server;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OAuth2ServerServiceProvider extends ServiceProvider {
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('lavoaster/oauth2server', 'lavoaster/oauth2server');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{

	}
}