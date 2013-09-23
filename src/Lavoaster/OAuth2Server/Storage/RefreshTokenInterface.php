<?php namespace Lavoaster\OAuth2Server\Storage;

interface RefreshTokenInterface extends TokenInterface
{
	/**
	 * Returns the refresh token
	 *
	 * @return string
	 */
	public function getRefreshToken();

	/**
	 * Sets the refresh token
	 *
	 * @param string $code
	 * @return void
	 */
	public function setRefreshToken($code);
}