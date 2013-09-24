<?php namespace Lavoaster\OAuth2Server;

use Lavoaster\OAuth2Server\Repositories\AccessTokenRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface;
use Lavoaster\OAuth2Server\Repositories\Eloquent\AuthorizationCodeRepository;
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
	 * @var Repositories\Eloquent\AuthorizationCodeRepository
	 */
	protected $authorizationCodeRepository;

	public function __construct(
		OAuthUserRepositoryInterface $userRepository,
		AccessTokenRepositoryInterface $accessTokenRepository,
		RefreshTokenRepositoryInterface $refreshTokenRepository,
		ClientRepositoryInterface $clientRepository,
		AuthorizationCodeRepository $authorizationCodeRepository
	)
	{
		$this->userRepository = $userRepository;
		$this->accessTokenRepository = $accessTokenRepository;
		$this->authorizationCodeRepository = $authorizationCodeRepository;
		$this->refreshTokenRepository = $refreshTokenRepository;
		$this->clientRepository = $clientRepository;
		$this->authorizationCodeRepository = $authorizationCodeRepository;
	}
}