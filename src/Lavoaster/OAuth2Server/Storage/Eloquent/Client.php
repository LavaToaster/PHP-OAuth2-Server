<?php namespace Lavoaster\OAuth2Server\Storage\Eloquent;

use Lavoaster\OAuth2Server\Storage\ClientInterface;

class Client extends \Eloquent implements ClientInterface
{
	protected $scopeArray = [];
	protected $redirectUriArray = [];

	/**
	 * Returns the clients id
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Sets the clients id
	 *
	 * @param string $id
	 * @return void
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Returns the clients secret
	 *
	 * @return string
	 */
	public function getSecret()
	{
		return $this->secret;
	}

	/**
	 * Sets the clients secret
	 *
	 * @param string $secret
	 * @return void
	 */
	public function setSecret($secret)
	{
		$this->secret = $secret;
	}

	/**
	 * Returns the clients supported scopes
	 *
	 * @return array
	 */
	public function getSupportedScopes()
	{
		if(!count($this->scopeArray) && !empty($this->supported_scopes)) {
			$this->scopeArray = explode(' ', $this->supported_scopes);
		}

		return $this->scopeArray;
	}

	/**
	 * Sets the supported scopes the client can use
	 *
	 * @param array $scopes
	 * @return void
	 */
	public function setSupportedScopes(array $scopes)
	{
		$this->supported_scopes = $this->scopeArray = $scopes;
	}

	/**
	 * Checks if the client has access to a given scope
	 *
	 * @param string $scope
	 * @return bool
	 */
	public function hasScope($scope)
	{
		return in_array($scope, $this->getSupportedScopes());
	}

	/**
	 * Checks if the client has access to a set of scopes
	 *
	 * @param array $scopes
	 * @return bool
	 */
	public function hasScopes(array $scopes)
	{
		foreach($scopes as $scope) {
			if(!$this->hasScope($scope)) return false;
		}

		return true;
	}

	/**
	 * Returns all of the clients redirect uris
	 *
	 * @return array
	 */
	public function getRedirectUris()
	{
		if(!count($this->redirectUriArray)) {
			$this->scopeArray = explode(' ', $this->redirect_uris);
		}

		return $this->redirectUriArray;
	}

	/**
	 * Sets the clients redirect uris
	 *
	 * @param array $redirectUris
	 * @return void
	 */
	public function setRedirectUris(array $redirectUris)
	{
		/* Just to make sure that we don't accidentally end up with dupes */
		$redirectUris = array_unique($redirectUris);

		$this->redirect_uris = implode(' ', $redirectUris);
		$this->redirectUriArray = $redirectUris;
	}

	/**
	 * Adds a redirect uri to the clients supported redirect uri's
	 *
	 * @param string $redirectUri
	 * @return void
	 */
	public function addRedirectUri($redirectUri)
	{
		$this->setRedirectUris(array_merge($this->getRedirectUris(), [$redirectUri]));
	}

	/**
	 * Removes the given redirect uri from the client
	 *
	 * @param string $redirectUri
	 * @return void
	 */
	public function removeRedirectUri($redirectUri)
	{
		if(($key = array_search($redirectUri, $this->getRedirectUris())) !== false) {
			unset($this->redirectUriArray[$key]);
			$this->setRedirectUris($this->redirectUriArray);
		}
	}

	/**
	 * Checks if the given redirect uri is resisted with the client
	 *
	 * @param string $redirectUri
	 * @return bool
	 */
	public function checkRedirectUri($redirectUri)
	{
		return in_array($redirectUri, $this->getRedirectUris());
	}

	/**
	 * Returns the client type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Sets the client type
	 *
	 * @param string $type
	 * @return mixed
	 */
	public function setType($type)
	{
		$this->type = $type;
	}
}