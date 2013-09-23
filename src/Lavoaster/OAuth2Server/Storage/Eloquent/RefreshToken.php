<?php namespace Lavoaster\OAuth2Server\Storage\Eloquent;

use Lavoaster\OAuth2Server\Storage\Eloquent\Traits\TokenTrait;
use Lavoaster\OAuth2Server\Storage\RefreshTokenInterface;

class RefreshToken extends \Eloquent implements RefreshTokenInterface
{
	use TokenTrait;

	/**
	 * Returns the refresh token
	 *
	 * @return string
	 */
	public function getRefreshToken()
	{
		return $this->refresh_token;
	}

	/**
	 * Sets the refresh token
	 *
	 * @param string $refreshToken
	 * @return void
	 */
	public function setRefreshToken($refreshToken)
	{
		$this->refresh_token = $refreshToken;
	}
}