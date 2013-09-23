<?php namespace Lavoaster\OAuth2Server\Repositories\Eloquent;

use Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface;
use Lavoaster\OAuth2Server\Storage\ClientInterface;
use Lavoaster\OAuth2Server\Storage\Eloquent\Client;

class ClientRepository implements ClientRepositoryInterface
{
	protected $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * Creates a new client with the given attributes
	 *
	 * @param array $attributes
	 * @return ClientInterface
	 */
	public function create(array $attributes)
	{
		return $this->client->create($attributes);
	}

	/**
	 * Finds a client by their id
	 *
	 * @param string $clientId
	 * @return ClientInterface
	 */
	public function find($clientId)
	{
		return $this->client->find($clientId);
	}
}