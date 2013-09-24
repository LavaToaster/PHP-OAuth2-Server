<?php namespace Lavoaster\OAuth2Server\Repositories;

use Lavoaster\OAuth2Server\Storage\RefreshTokenInterface;

interface RefreshTokenRepositoryInterface
{
	/**
	 * Creates a new refresh token with the given attributes
	 *
	 * @param array $attributes
	 * @return RefreshTokenInterface
	 */
	public function create(array $attributes);

	/**
	 * Finds a refresh token
	 *
	 * @param string $refreshToken
	 * @return RefreshTokenInterface
	 */
	public function find($refreshToken);
}