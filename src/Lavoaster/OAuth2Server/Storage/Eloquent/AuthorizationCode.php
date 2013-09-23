<?php namespace Lavoaster\OAuth2Server\Storage\Eloquent;

use Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface;
use Lavoaster\OAuth2Server\Storage\Eloquent\Traits\TokenTrait;

class AuthorizationCode extends \Eloquent implements AuthorizationCodeInterface
{
	use TokenTrait;

	/**
	 * Returns the authorization code
	 *
	 * @return string
	 */
	public function getAuthorizationCode()
	{
		return $this->authorization_code;
	}

	/**
	 * Sets the authorization code
	 *
	 * @param string $code
	 * @return void
	 */
	public function setAuthorizationCode($code)
	{
		$this->authorization_code = $code;
	}

	/**
	 * Returns the redirect uri
	 *
	 * @return string
	 */
	public function getRedirectUri()
	{
		return $this->redirect_uri;
	}

	/**
	 * Sets the redirect uri
	 *
	 * @param $redirectUri
	 * @return void
	 */
	public function setRedirectUri($redirectUri)
	{
		$this->redirect_uri = $redirectUri;
	}


}