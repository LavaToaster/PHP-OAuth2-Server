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

		include __DIR__.'/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerPasswordValidator();
		$this->registerStorageDriver();
		$this->registerOAuth2Server();
	}

	protected function registerPasswordValidator()
	{
		$this->app['oauth2server.password_validator'] = $this->app->share(function (Application $app)
		{
			$this->app->singleton(
				'Lavoaster\OAuth2Server\User\UserPasswordValidatorInterface',
				$this->app['config']['lavoaster/oauth2server::users.password_validator']
			);

			return $app->make('Lavoaster\OAuth2Server\User\UserPasswordValidatorInterface');
		});
	}

	protected function registerOAuth2Server()
	{
		$this->app['Lavoaster\OAuth2Server\OAuth2Server'] = $this->app->share(function(Application $app)
		{
			return new OAuth2Server(
				$app['oauth2server.storage'],
				$app->make('OAuth2\Server'),
				$app->make('Lavoaster\OAuth2Server\HttpBridge\Response'),
				$app['config']['lavoaster/oauth2server::grant_types']
			);
		});
	}

	protected function registerStorageDriver()
	{
		$this->app['oauth2server.storage'] = $this->app->share(function(Application $app)
		{
			$app->singleton('Lavoaster\OAuth2Server\Storage\Laravel', function()
			{
				return new Storage\Laravel([
						'user_table'            => \Config::get('lavoaster/oauth2server::users.table'),
						'user_id'               => \Config::get('lavoaster/oauth2server::users.user_id'),
						'user_attribute'        => \Config::get('lavoaster/oauth2server::users.login_attribute'),
					], $this->app['oauth2server.password_validator']
				);
			});

			return $app->make('Lavoaster\OAuth2Server\Storage\Laravel');
		});

		/*$this->app->singleton('OAuth2\Storage\AuthorizationCodeInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');

		$this->app->singleton('OAuth2\Storage\AccessTokenInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');

		$this->app->singleton('OAuth2\Storage\ClientCredentialsInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');

		$this->app->singleton('OAuth2\Storage\JwtBearerInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');

		$this->app->singleton('OAuth2\Storage\RefreshTokenInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');

		$this->app->singleton('OAuth2\Storage\ScopeInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');

		$this->app->singleton('OAuth2\Storage\UserCredentialsInterface', 'Lavoaster\OAuth2Server\Storage\Laravel');*/
	}

}