<?php namespace Lavoaster\OAuth2Server\Storage\Eloquent\Traits;

use Lavoaster\OAuth2Server\Storage\ClientInterface;
use Lavoaster\OAuth2Server\Storage\OAuthUserInterface;

trait TokenTrait
{
	protected $scopeArray = [];

	/**
	 * Returns the relationship to the client
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function client()
	{
		return $this->hasOne('oauth_clients');
	}

	/**
	 * Returns the OAuth Client
	 *
	 * @return ClientInterface
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Sets the client that owns the token
	 *
	 * @param ClientInterface $client
	 * @return void
	 */
	public function setClient(ClientInterface $client)
	{
		$this->client_id = $client->getId();
	}

	/**
	 * Returns the relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
	public function user()
	{
		return $this->hasOne(\Config::get('lavoaster/oauth2server::user.table'), \Config::get('lavoaster/oauth2server::user.id'));
	}

	/**
	 * Returns the user bound to the token
	 *
	 * @return OAuthUserInterface
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Sets the user assigned to the token
	 *
	 * @param OAuthUserInterface $user
	 * @return void
	 */
	public function setUser(OAuthUserInterface $user)
	{
		$this->user_id = $user->getId();
	}

	/**
	 * Returns the token expiry date
	 *
	 * @return int
	 */
	public function getExpiry()
	{
		return strtotime($this->expires);
	}

	/**
	 * Sets the tokens expiry date
	 *
	 * @param int $expiry
	 * @return void
	 */
	public function setExpiry($expiry)
	{
		$this->expires = date("Y-m-d H:i:s", $expiry);
	}

	/**
	 * Checks if the access token is expired
	 *
	 * @return bool
	 */
	public function isExpired()
	{
		return $this->getExpiry() < time();
	}

	/**
	 * Returns the scopes the token has access to
	 *
	 * @return array
	 */
	public function getScopes()
	{
		if(!count($this->scopeArray) && !empty($this->scopes)) {
			$this->scopeArray = explode(' ', $this->scopes);
		}

		return $this->scopeArray;
	}

	/**
	 * Sets the scopes on the access token
	 *
	 * @param array $scopes
	 * @return void
	 */
	public function setScopes(array $scopes)
	{
		$this->scopes = implode(' ', $scopes);
		$this->scopeArray = $scopes;
	}

	/**
	 * Checks if the token has access to a given scope
	 *
	 * @param string $scope
	 * @return bool
	 */
	public function hasScope($scope)
	{
		return in_array($scope, $this->getScopes());
	}
}