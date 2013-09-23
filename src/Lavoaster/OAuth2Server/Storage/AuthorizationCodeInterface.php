<?php namespace Lavoaster\OAuth2Server\Storage;

interface AuthorizationCodeInterface extends TokenInterface
{
	/**
	 * Returns the authorization code
	 *
	 * @return string
	 */
	public function getAuthorizationCode();

	/**
	 * Sets the authorization code
	 *
	 * @param string $code
	 * @return void
	 */
	public function setAuthorizationCode($code);

	/**
	 * Returns the redirect uri
	 *
	 * @return string
	 */
	public function getRedirectUri();

	/**
	 * Sets the redirect uri
	 *
	 * @param $redirectUri
	 * @return void
	 */
	public function setRedirectUri($redirectUri);
}