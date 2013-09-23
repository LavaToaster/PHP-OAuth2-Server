<?php namespace Lavoaster\OAuth2Server\Storage;

interface ClientInterface
{
	/**
	 * Returns the clients id
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Sets the clients id
	 *
	 * @param string $id
	 * @return void
	 */
	public function setId($id);

	/**
	 * Returns the clients secret
	 *
	 * @return string
	 */
	public function getSecret();

	/**
	 * Sets the clients secret
	 *
	 * @param string $secret
	 * @return void
	 */
	public function setSecret($secret);

	/**
	 * Returns the clients supported scopes
	 *
	 * @return array
	 */
	public function getSupportedScopes();

	/**
	 * Sets the supported scopes the client can use
	 *
	 * @param array $scopes
	 * @return void
	 */
	public function setSupportedScopes(array $scopes);

	/**
	 * Returns all of the clients redirect uris
	 *
	 * @return array
	 */
	public function getRedirectUris();

	/**
	 * Sets the clients redirect uris
	 *
	 * @param array $redirectUri
	 * @return void
	 */
	public function setRedirectUris(array $redirectUri);

	/**
	 * Adds a redirect uri to the clients supported redirect uri's
	 *
	 * @param string $redirectUri
	 * @return void
	 */
	public function addRedirectUri($redirectUri);

	/**
	 * Removes the given redirect uri from the client
	 *
	 * @param string $redirectUri
	 * @return void
	 */
	public function removeRedirectUri($redirectUri);

	/**
	 * Checks if the given redirect uri is resisted with the client
	 *
	 * @param string $redirectUri
	 * @return bool
	 */
	public function checkRedirectUri($redirectUri);
}