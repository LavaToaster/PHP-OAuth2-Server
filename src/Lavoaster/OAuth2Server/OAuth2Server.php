<?php namespace Lavoaster\OAuth2Server;

use Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Response as LaravelResponse;
use Lavoaster\OAuth2Server\HttpBridge\Request;
use Lavoaster\OAuth2Server\HttpBridge\Response;
use Lavoaster\OAuth2Server\Storage\Laravel;
use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\JwtBearer;
use OAuth2\GrantType\UserCredentials;
use OAuth2\Server;

class OAuth2Server {

	/**
	 * @var Storage\Laravel
	 */
	protected $storageDriver;

	/**
	 * @var Server
	 */
	protected $server;

	/**
	 * @var array Grant types clients can request
	 */
	protected $activeGrantTypes = [];

	/**
	 * @var array Grant types available for use
	 */
	protected $availableGrantTypes = [
		'authorization_code',
		'client_credentials',
		'urn:ietf:params:oauth:grant-type:jwt-bearer',
		'refresh_token',
		'password'
	];

	/**
	 * Stores the resource errors
	 *
	 * @var JsonResponse
	 */
	protected $resourceError;

	protected $response;

	public function __construct(Laravel $storageDriver, Server $server, Response $response, array $grantTypes)
	{
		$this->storageDriver = $storageDriver;
		$this->server = $server;
		$this->addGrantType($grantTypes);
		$this->response = $response;

		$this->server->addStorage($storageDriver);
	}

	/**
	 * Gets the available grant types for use
	 *
	 * @return array
	 */
	public function getAvailableGrantTypes()
	{
		return $this->availableGrantTypes;
	}

	/**
	 * Gets the active grant types that can be used
	 *
	 * @return array
	 */
	public function getActiveGrantTypes()
	{
		return $this->activeGrantTypes;
	}

	/**
	 * Adds a grant types that can be used
	 *
	 * Available:
	 *      - authorization_code
	 *      - client_credentials
	 *      - refresh_token
	 *      - password
	 *      - urn:ietf:params:oauth:grant-type:jwt-bearer
	 *
	 * @param mixed $grant
	 * @throws \Exception
	 */
	public function addGrantType($grant)
	{
		if (is_array($grant)) {
			foreach ($grant as $type) {
				if (in_array($type, $this->availableGrantTypes) && !in_array($type, $this->activeGrantTypes)) {
					$this->server->addGrantType($this->getGrantClass($type));
					$this->activeGrantTypes[] = $type;
				}
			}
		} else if (in_array($grant, $this->availableGrantTypes) && !in_array($grant, $this->activeGrantTypes)) {
			$this->server->addGrantType($this->getGrantClass($grant));
			$this->activeGrantTypes[] = $grant;
		} else {
			throw new \Exception('Invalid grant type ['.$grant.']');
		}
	}

	/**
	 * Removes a grant type from the available set
	 *
	 * @param $grant
	 */
	public function removeGrantType($grant)
	{
		if(($key = array_search($grant, $this->activeGrantTypes)) !== false) {
			unset($this->activeGrantTypes[$key]);
		}
	}

	/**
	 * Gets the class for the given grant type
	 *
	 * @param $type
	 * @return AuthorizationCode|ClientCredentials|JwtBearer
	 * @throws \InvalidArgumentException
	 */
	protected function getGrantClass($type)
	{
		switch ($type)
		{
			case 'authorization_code':
				return new AuthorizationCode($this->storageDriver);
				break;
			case 'client_credentials':
				return new ClientCredentials($this->storageDriver);
				break;
			case 'urn:ietf:params:oauth:grant-type:jwt-bearer':
				return new JwtBearer($this->storageDriver, Config::get('laravel-oauth2-server::jwt_audience'));
				break;
			case 'refresh_token':
				return new AuthorizationCode($this->storageDriver);
				break;
			case 'password':
				return new UserCredentials($this->storageDriver);
				break;
		}

		throw new \InvalidArgumentException;
	}

	/**
	 * Handles token requests
	 *
	 * @return \Illuminate\Http\JsonResponse|RedirectResponse
	 */
	public function handleTokenRequest()
	{
		$response = new Response;

		$this->server->handleTokenRequest(Request::createFromRequest(), $response);

		return $this->handleResponse($response);
	}

	/**
	 * Handles authorization requests
	 *
	 * @param $is_authorized
	 * @param $user_id
	 * @return JsonResponse|RedirectResponse
	 */
	public function handleAuthorizeRequest($is_authorized, $user_id)
	{
		$response = new Response;

		$this->server->handleAuthorizeRequest(Request::createFromRequest(), $response, $is_authorized, $user_id);

		return $this->handleResponse($response);
	}

	/**
	 * Parses the response received to see if it needs to be converted to a
	 * redirect response.
	 *
	 * @return JsonResponse|RedirectResponse
	 */
	public function handleResponse()
	{
		if ($this->response->headers->has('Location')) {
			$redirect = \Redirect::to($$this->response->headers->get('Location'), $this->response->getStatusCode(), $this->response->headers->allPreserveCase());

			$redirect->setTargetUrl($redirect->getTargetUrl() . "?" . http_build_query(json_decode($this->response->getContent())));

			$this->response = $redirect;
		}

		return $this->response;
	}

	/**
	 * Verifies the incoming resource request.
	 *
	 * @param string $scope
	 * @return bool|JsonResponse Returns true if verified, JsonResponse on failure.
	 */
	public function verifyResourceRequest($scope = null)
	{
		$return = $this->server->verifyResourceRequest(Request::createFromRequest(), $this->response, $scope);

		return $return;
	}

	public function getResponse()
	{
		return $this->response;
	}
}