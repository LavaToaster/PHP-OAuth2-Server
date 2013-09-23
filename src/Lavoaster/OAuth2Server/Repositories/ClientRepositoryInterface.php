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
	 * Finds a client by their id
	 *
	 * @param string $clientId
	 * @return ClientInterface
	 */
	public function find($clientId);
}