<?php namespace Lavoaster\OAuth2Server\Storage;

interface AccessTokenInterface extends TokenInterface
{
	/**
	 * Returns the access token
	 *
	 * @return string
	 */
	public function getAccessToken();

	/**
	 * Sets the access token
	 *
	 * @param string $accessToken
	 * @return void
	 */
	public function setAccessToken($accessToken);
}