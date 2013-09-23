<?php namespace Lavoaster\OAuth2Server\Repositories\Eloquent;

use Lavoaster\OAuth2Server\Repositories\RefreshTokenRepositoryInterface;
use Lavoaster\OAuth2Server\Storage\RefreshTokenInterface;
use Lavoaster\OAuth2Server\Storage\Eloquent\RefreshToken;

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
	protected $refreshToken;

	public function __construct(RefreshToken $refreshToken)
	{
		$this->refreshToken = $refreshToken;
	}

	/**
	 * Creates a new refresh token with the given attributes
	 *
	 * @param array $attributes
	 * @return RefreshTokenInterface
	 */
	public function create(array $attributes)
	{
		return $this->refreshToken->create($attributes);
	}

	/**
	 * Finds an refresh token
	 *
	 * @param string $refreshToken
	 * @return RefreshTokenInterface
	 */
	public function find($refreshToken)
	{
		return $this->refreshToken->find($refreshToken);
	}


}