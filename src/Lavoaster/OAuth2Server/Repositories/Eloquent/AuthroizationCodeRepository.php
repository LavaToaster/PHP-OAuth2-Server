<?php namespace Lavoaster\OAuth2Server\Repositories\Eloquent;

use Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface;
use Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface;
use Lavoaster\OAuth2Server\Storage\Eloquent\AuthorizationCode;

class AuthorizationCodeRepository implements AuthorizationCodeRepositoryInterface
{
	protected $authorizationCode;

	public function __construct(AuthorizationCode $authorizationCode)
	{
		$this->authorizationCode = $authorizationCode;
	}

	/**
	 * Creates a new authorization Code with the given attributes
	 *
	 * @param array $attributes
	 * @return AuthorizationCodeInterface
	 */
	public function create(array $attributes)
	{
		$this->authorizationCode->create($attributes);
	}

	/**
	 * Finds an authorization Code
	 *
	 * @param string $authorizationCode
	 * @return AuthorizationCodeInterface
	 */
	public function find($authorizationCode)
	{
		$this->authorizationCode->find($authorizationCode);
	}

}