<?php namespace Lavoaster\OAuth2Server\Facade;

use Illuminate\Support\Facades\Facade;

class OAuth2Server extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'oauth2server';
	}
}