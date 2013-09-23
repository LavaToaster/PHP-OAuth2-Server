<?php namespace Lavoaster\OAuth2Server\Storage\Eloquent;

use Lavoaster\OAuth2Server\Storage\AccessTokenInterface;
use Lavoaster\OAuth2Server\Storage\Eloquent\Traits\TokenTrait;

class AccessToken extends \Eloquent implements AccessTokenInterface
{
	use TokenTrait;

	/**
	 * Returns the access token
	 *
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->access_token;
	}

	/**
	 * Sets the access token
	 *
	 * @param string $accessToken
	 * @return void
	 */
	public function setAccessToken($accessToken)
	{
		$this->access_token = $accessToken;
	}
}