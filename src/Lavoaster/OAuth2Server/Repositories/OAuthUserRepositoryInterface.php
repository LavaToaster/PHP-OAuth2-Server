<?php namespace Lavoaster\OAuth2Server\Repositories;

use Lavoaster\OAuth2Server\Storage\OAuthUserInterface;

interface OAuthUserRepositoryInterface
{
	/**
	 * Finds a user by their id
	 *
	 * @param int $id
	 * @return OAuthUserInterface
	 */
	public function find($id);

	/**
	 * Finds a user by their identifier. Such as username, email, etc...
	 *
	 * @param string $identifier
	 * @return OAuthUserInterface
	 */
	public function findByIdentifier($identifier);
}