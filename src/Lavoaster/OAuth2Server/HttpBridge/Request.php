<?php namespace Lavoaster\OAuth2Server\HttpBridge;

use Request as LaravelRequest;
use OAuth2\RequestInterface;

class Request implements RequestInterface
{
	public function query($name, $default = null)
	{
		return LaravelRequest::query($name, $default);
	}

	public function request($name, $default = null)
	{
		return LaravelRequest::get($name, $default);
	}

	public function server($name, $default = null)
	{
		return LaravelRequest::server($name, $default);
	}

	public function headers($name, $default = null)
	{
		return LaravelRequest::header($name, $default);
	}

	public function getAllQueryParameters()
	{
		return LaravelRequest::all();
	}

	public static function createFromRequest()
	{
		return new Request;
	}

}