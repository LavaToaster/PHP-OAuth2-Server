<?php namespace Lavoaster\OAuth2Server\Repositories;

use Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface;

interface AuthorizationCodeRepositoryInterface
{
	/**
	 * Creates a new authorization Code with the given attributes
	 *
	 * @param array $attributes
	 * @return AuthorizationCodeInterface
	 */
	public function create(array $attributes);

	/**
	 * Finds an authorization Code
	 *
	 * @param string $authorizationCode
	 * @return AuthorizationCodeInterface
	 */
	public function find($authorizationCode);
}