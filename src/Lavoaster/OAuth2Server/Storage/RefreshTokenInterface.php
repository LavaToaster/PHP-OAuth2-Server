<?php namespace Lavoaster\OAuth2Server\Storage;

interface RefreshTokenInterface
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

	/**
	 * Returns the OAuth Client
	 *
	 * @return ClientInterface
	 */
	public function getClient();

	/**
	 * Sets the client that owns the token
	 *
	 * @param ClientInterface $client
	 * @return void
	 */
	public function setClient(ClientInterface $client);

	/**
	 * Returns the user bound to the token
	 *
	 * @return OAuthUserInterface
	 */
	public function getUser();

	/**
	 * Sets the user assigned to the token
	 *
	 * @param OAuthUserInterface $user
	 * @return void
	 */
	public function setUser(OAuthUserInterface $user);

	/**
	 * Returns the token expiry date
	 *
	 * @return string
	 */
	public function getExpiry();

	/**
	 * Sets the tokens expiry date
	 *
	 * @param string $expiry
	 * @return void
	 */
	public function setExpiry($expiry);

	/**
	 * Checks if the refresh token is expired
	 *
	 * @return bool
	 */
	public function isExpired();

	/**
	 * Returns the scopes the token has refresh to
	 *
	 * @return array
	 */
	public function getScopes();

	/**
	 * Sets the scopes on the refresh token
	 *
	 * @param array $scopes
	 * @return void
	 */
	public function setScopes(array $scopes);

	/**
	 * Checks if the token has refresh to a given scope
	 *
	 * @param string $scope
	 * @return bool
	 */
	public function hasScope($scope);
}