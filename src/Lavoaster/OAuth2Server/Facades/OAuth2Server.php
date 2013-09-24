<?php namespace Lavoaster\OAuth2Server\Facades;

use Illuminate\Support\Facades\Facade;

class OAuth2Server extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'oauth2server';
	}
}