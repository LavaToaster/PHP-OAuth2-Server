<?php namespace Lavoaster\OAuth2Server\Repositories;

use Lavoaster\OAuth2Server\Storage\ClientInterface;

interface ClientRepositoryInterface
{
	/**
	 * Creates a new client with the given attributes
	 *
	 * @param array $attributes
	 * @return ClientInterface
	 */
	public function create(array $attributes);

	/**
	 * Finds an access token
	 *
	 * @param string $accessToken
	 * @return ClientInterface
	 */
	public function find($accessToken);
}