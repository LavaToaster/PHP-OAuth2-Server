<?php namespace Lavoaster\OAuth2Server\Repositories;

use Lavoaster\OAuth2Server\Storage\AccessTokenInterface;

interface AccessTokenRepositoryInterface
{
	/**
	 * Creates a new access token with the given attributes
	 *
	 * @param array $attributes
	 * @return AccessTokenInterface
	 */
	public function create(array $attributes);

	/**
	 * Finds an access token
	 *
	 * @param string $accessToken
	 * @return AccessTokenInterface
	 */
	public function find($accessToken);
}