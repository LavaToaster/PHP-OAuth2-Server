<?php namespace Lavoaster\OAuth2Server\Repositories\Eloquent;

use Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterface;
use Lavoaster\OAuth2Server\Storage\AccessTokenInterface;
use Lavoaster\OAuth2Server\Storage\Eloquent\AccessToken;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
	protected $accessToken;

	public function __construct(AccessToken $accessToken)
	{
		$this->accessToken = $accessToken;
	}

	/**
	 * Creates a new access token with the given attributes
	 *
	 * @param array $attributes
	 * @return AccessTokenInterface
	 */
	public function create(array $attributes)
	{
		return $this->accessToken->create($attributes);
	}

	/**
	 * Finds an access token
	 *
	 * @param string $accessToken
	 * @return AccessTokenInterface
	 */
	public function find($accessToken)
	{
		return $this->accessToken->find($accessToken);
	}


}