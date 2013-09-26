<?php namespace Lavoaster\OAuth2Server\Request;

use Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface;
use Lavoaster\OAuth2Server\Response\Response;
use Lavoaster\OAuth2Server\Storage\ClientInterface;
use Lavoaster\OAuth2Server\Storage\OAuthUserInterface;

class AuthorizationRequest
{
	/**
	 * @var \Lavoaster\OAuth2Server\Repositories\Eloquent\AuthorizationCodeRepository
	 */
	protected $authorizationCodeRepository;

	/**
	 * @var \Lavoaster\OAuth2Server\Repositories\Eloquent\ClientRepository
	 */
	protected $clientRepository;

	/**
	 * @var \Lavoaster\OAuth2Server\Storage\ClientInterface
	 */
	protected $client;

	/**
	 * @var \Illuminate\Support\Str
	 */
	protected $str;

	protected $error;

	protected $requestDetails = [
		'response_type' => null,
		'client_id'     => null,
		'redirect_uri'  => null,
		'scope'         => null,
		'state'         => null,
	];

	protected $config = [];

	/**
	 * AuthorizationRequest Class
	 *
	 * @param array $requestDetails
	 * @param array $configuration
	 * @param AuthorizationCodeRepositoryInterface $authorizationCodeRepository
	 * @param ClientRepositoryInterface $clientRepository
	 */
	public function __construct(
		array $requestDetails,
		array $configuration,
		AuthorizationCodeRepositoryInterface $authorizationCodeRepository,
		ClientRepositoryInterface $clientRepository,
		\Illuminate\Support\Str $str
	) {
		$this->requestDetails = array_merge($this->requestDetails, $requestDetails);
		$this->config = $configuration;
		$this->authorizationCodeRepository = $authorizationCodeRepository;
		$this->clientRepository = $clientRepository;
		$this->str = $str;
	}

	/**
	 * Validates the AuthorizationRequest
	 *
	 * @return bool
	 */
	public function validateRequest()
	{
		if (!$this->validateRequiredParams()) {
			return false;
		}

		if (!$client = $this->clientRepository->find($this->requestDetails['client_id'])) {
			return $this->error(400, 'invalid_request', "Client id [{$this->requestDetails['client_id']}] not found");
		}

		if ($this->requestDetails['redirect_uri'] && !$this->validateRedirectUri($this->requestDetails['redirect_uri'], $client)) {
			return false;
		}

		if ($this->requestDetails['scope'] && !$this->validateScope($this->requestDetails['scope'], $client)) {
			return false;
		}

		return true;
	}

	/**
	 * Validates that the request has ALL required parameters
	 *
	 * @return bool
	 */
	public function validateRequiredParams()
	{
		if (!$this->requestDetails['response_type']) {
			return $this->error(302, 'invalid_request', 'Required parameter response_type is missing', 'http://tools.ietf.org/html/rfc6749#section-4.1.1');
		}

		/* According to the RFC this MUST be set to code even though it is the only value at this time */
		if ($this->requestDetails['response_type'] != 'code') {
			return $this->error(302, 'unsupported_response_type', 'response_type parameter MUST be set to code', 'http://tools.ietf.org/html/rfc6749#section-4.1.1');
		}

		/**
		 * Missing client_id or redirect_uri must inform resource owner and not redirect back to client.
		 * @link http://tools.ietf.org/html/rfc6749#section-4.1.2.1
		 */
		if (!$this->requestDetails['client_id']) {
			return $this->error(400, 'invalid_request', 'Required parameter client_id missing', 'http://tools.ietf.org/html/rfc6749#section-4.1.1');
		}

		if (!$this->requestDetails['redirect_uri'] && $this->config['oauth']['enforce_redirect_uri']) {
			return $this->error(400, 'invalid_request', 'Required parameter redirect_uri was not present');
		}

		if (!$this->requestDetails['scope'] && !$this->config['oauth']['default_scope']) {
			return $this->error(302, 'invalid_request', 'Required parameter scope was not present');
		}

		if (!$this->requestDetails['state'] && $this->config['oauth']['enforce_state']) {
			return $this->error(302, 'invalid_request', 'Required parameter state was not present');
		}

		return true;
	}

	/**
	 * Validates that the contents of the redirect_uri parameter is registered
	 * with the client
	 *
	 * @param string $redirectUri
	 * @param ClientInterface $client
	 * @return bool
	 */
	public function validateRedirectUri($redirectUri, ClientInterface $client)
	{
		if (!$client->checkRedirectUri($redirectUri)) {
			return $this->error(400, 'invalid_request', "Given Redirect uri [{$this->requestDetails['redirect_uri']}] is not registered with the client");
		}

		return true;
	}

	/**
	 * Validates that the client has access to the requested scopes
	 *
	 * @param string $scope
	 * @param ClientInterface $client
	 * @return bool
	 */
	public function validateScope($scope, ClientInterface $client)
	{
		if(!$client->hasScopes(explode(' ', $scope))) {
			return $this->error(302, 'invalid_scope', "Given scope set [{$scope}] is invalid, unknown or malformed");
		}

		return true;
	}

	/**
	 * Returns the errors encountered during validation.
	 * All other errors should result in a redirect, with "?error=server_error" appended to the redirect_uri
	 *
	 * @return array|null
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Helper method to set the error message and return false at the time
	 *
	 * @param int $status
	 * @param string $error
	 * @param string $error_description
	 * @param string $error_uri
	 * @return bool always false
	 */
	public function error($status, $error, $error_description = '', $error_uri = '')
	{
		$this->error = [
			'status' => $status,
			'error' => $error,
			'error_description' => $error_description,
			'error_uri' => $error_uri
		];

		return false;
	}
}