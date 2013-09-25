<?php namespace Lavoaster\OAuth2Server;

use Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\OAuthUserRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\RefreshTokenRepositoryInterface;

class OAuth2Server {

	/**
	 * @var Repositories\OAuthUserRepositoryInterface
	 */
	protected $userRepository;

	/**
	 * @var Repositories\AccessTokenRepositoryInterface
	 */
	protected $accessTokenRepository;

	/**
	 * @var Repositories\RefreshTokenRepositoryInterface
	 */
	protected $refreshTokenRepository;

	/**
	 * @var Repositories\ClientRepositoryInterface
	 */
	protected $clientRepository;

	/**
	 * @var Repositories\AuthorizationCodeRepositoryInterface
	 */
	protected $authorizationCodeRepository;

	protected $config = [];

	public function __construct(
		$configuration,
		OAuthUserRepositoryInterface $userRepository,
		AccessTokenRepositoryInterface $accessTokenRepository,
		RefreshTokenRepositoryInterface $refreshTokenRepository,
		ClientRepositoryInterface $clientRepository,
		AuthorizationCodeRepositoryInterface $authorizationCodeRepository
	) {
		$this->config = $configuration;
		$this->userRepository = $userRepository;
		$this->accessTokenRepository = $accessTokenRepository;
		$this->authorizationCodeRepository = $authorizationCodeRepository;
		$this->refreshTokenRepository = $refreshTokenRepository;
		$this->clientRepository = $clientRepository;
		$this->authorizationCodeRepository = $authorizationCodeRepository;
	}
}