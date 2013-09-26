<?php

namespace {
	class AuthorizeRequestTest extends PHPUnit_Framework_TestCase
	{
		public function testValidateRequiredParamsReturnsFalseWhenResponseTypeIsMissing()
		{
			$class = $this->getClass($this->getRequest(['response_type' => null]), $this->getConfig());

			$this->assertFalse($class->validateRequiredParams());
			$this->assertEquals(302, $class->getError()['status']);
		}

		public function testValidateRequiredParamsReturnsFalseWhenResponseTypeIsNotCode()
		{
			$class = $this->getClass($this->getRequest(['response_type' => 'notcode']), $this->getConfig());

			$this->assertFalse($class->validateRequiredParams());
			$this->assertEquals(302, $class->getError()['status']);
		}

		public function testValidateRequiredParamsReturnsFalseWhenClientIdMissing()
		{
			$class = $this->getClass($this->getRequest(['client_id' => null]), $this->getConfig());

			$this->assertFalse($class->validateRequiredParams());
			$this->assertEquals(400, $class->getError()['status']);
		}

		public function testValidateRequiredParamsReturnsFalseWhenRedirectUriIsMissingAndIsEnforced()
		{
			$class = $this->getClass($this->getRequest(['redirect_uri' => null]), $this->getConfig());

			$this->assertFalse($class->validateRequiredParams());
			$this->assertEquals(400, $class->getError()['status']);
		}

		public function testValidateRequiredParamsReturnsFalseWhenScopeIsMissingAndDefaultIsNull()
		{
			$class = $this->getClass($this->getRequest(['scope' => null]), $this->getConfig());

			$this->assertFalse($class->validateRequiredParams());
			$this->assertEquals(302, $class->getError()['status']);
		}

		public function testValidateRequiredParamsReturnsFalseWhenStateIsMissingAndEnforced()
		{
			$class = $this->getClass($this->getRequest(['state' => null]), $this->getConfig(true, true));

			$this->assertFalse($class->validateRequiredParams());
			$this->assertEquals(302, $class->getError()['status']);
		}

		public function testValidateRequiredParamsReturnsTrueWhenRedirectUriIsMissingAndIsNotEnforced()
		{
			$class = $this->getClass($this->getRequest(['redirect_uri' => null]), $this->getConfig(false));

			$this->assertTrue($class->validateRequiredParams());
		}

		public function testValidateRedirectUriCallsClientInterfaceAndFailsWhenRedirectUriIsInvalid()
		{
			$client = $this->getClient();
			$client->shouldReceive('checkRedirectUri')->once()->andReturn(false);

			$class = $this->getClass($this->getRequest(), $this->getConfig());

			$this->assertFalse($class->validateRedirectUri('http://localhost', $client));
			$this->assertEquals(400, $class->getError()['status']);
		}

		public function testValidateRedirectUriCallsClientInterfaceAndReturnsTrueWhenNormal()
		{
			$client = $this->getClient();
			$client->shouldReceive('checkRedirectUri')->once()->andReturn(true);

			$class = $this->getClass($this->getRequest(), $this->getConfig());

			$this->assertTrue($class->validateRedirectUri('http://localhost/', $client));
		}

		// TODO: Grammar fix, needs this does. HA, See what I did there?!
		public function testValidateScopeFailsWhenScopeIsNotSupportedForClient()
		{
			$client = $this->getClient();
			$client->shouldReceive('hasScopes')->with(['user'])->andReturn(false);
			$class = $this->getClass($this->getRequest(['scope']), $this->getConfig());

			$this->assertFalse($class->validateScope('user', $client));
			$this->assertEquals(302 ,$class->getError()['status']);
		}

		public function testValidateScopeReturnsTrueWhenScopeIsSupported()
		{
			$client = $this->getClient();
			$client->shouldReceive('hasScopes')->with(['user'])->andReturn(true);
			$class = $this->getClass($this->getRequest(['scope']), $this->getConfig());

			$this->assertTrue($class->validateScope('user', $client));
		}

		public function testValidateRequestReturnsFalseWhenClientIsNotFound()
		{
			$clientRepo = Mockery::mock('Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface');
			$clientRepo->shouldReceive('find')->with(1)->andReturnNull();
			$class = $this->getClass($this->getRequest(), $this->getConfig(), null, $clientRepo);

			$this->assertFalse($class->validateRequest());
		}

		public function testValidateRequestReturnsFalseRedirectUriIsPresentAndNotRegisteredWithClient()
		{
			$client = $this->getClient();
			$client->shouldReceive('checkRedirectUri')->andReturn(false);
			$clientRepo = Mockery::mock('Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface');
			$clientRepo->shouldReceive('find')->with(1)->andReturn($client);
			$class = $this->getClass($this->getRequest(), $this->getConfig(), null, $clientRepo);

			$this->assertFalse($class->validateRequest());
		}

		public function testValidateRequestReturnsFalseWhenScopeIsPresentAndClientDoesNotHaveAccessToIt()
		{
			$client = $this->getClient();
			$client->shouldReceive('checkRedirectUri')->once()->andReturn(true);
			$client->shouldReceive('hasScopes')->andReturn(false);
			$clientRepo = Mockery::mock('Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface');
			$clientRepo->shouldReceive('find')->with(1)->andReturn($client);
			$class = $this->getClass($this->getRequest(), $this->getConfig(), null, $clientRepo);

			$this->assertFalse($class->validateRequest());
		}

		public function testValidateRequestReturnsTrueWhenEverythingIsHunkyDory()
		{
			$client = $this->getClient();
			$client->shouldReceive('checkRedirectUri')->once()->andReturn(true);
			$client->shouldReceive('hasScopes')->andReturn(true);
			$clientRepo = Mockery::mock('Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface');
			$clientRepo->shouldReceive('find')->with(1)->andReturn($client);
			$class = $this->getClass($this->getRequest(), $this->getConfig(), null, $clientRepo);

			$this->assertTrue($class->validateRequest(), 'Everything wasn\'t hunky dory it seems :(');
		}

		public function testErrorIsSetWhenSomethingFails()
		{
			$error = [
				'status' => 302,
				'error' => 'unsupported_response_type',
				'error_description' => 'response_type parameter MUST be set to code',
				'error_uri' => 'http://tools.ietf.org/html/rfc6749#section-4.1.1'
			];

			$class = $this->getClass($this->getRequest(['response_type' => 'notcode']), $this->getConfig());

			$this->assertFalse($class->validateRequest());
			$this->assertEqualsArrays($class->getError(), $error, 'Error wasn\'t set properly');
		}

		public function testIssueCodeSetsEverythingAndReturnsAuthorizationCodeInterface()
		{
			$strClass = Mockery::mock('Illuminate\Support\Str');
			$strClass->shouldReceive('random')->with(40)->andReturn('thisisnotarandomcode');

			$user = Mockery::mock('Lavoaster\OAuth2Server\Storage\OAuthUserInterface');
			$user->shouldReceive('getId')->andReturn(23);

			$idealArray = [
				'authorization_code' => 'thisisnotarandomcode',
				'client_id'          => '1',
				'expires'            => Lavoaster\OAuth2Server\Request\strtotime("+1 Minute"),
				'user_id'            => 23,
				'scopes'             => 'user',
				'redirect_uri'       => 'http://localhost/'
			];

			$authCodeRepo = Mockery::mock('Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface');
			$authCodeRepo->shouldReceive('create')->with($idealArray)->andReturn(Mockery::mock('Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface'));

			$class = $this->getClass($this->getRequest(), $this->getConfig(), $authCodeRepo, null, $strClass);

			$this->assertInstanceOf('Lavoaster\OAuth2Server\Storage\AuthorizationCodeInterface', $class->issueCode($user));
		}

		/*****************************************HELPER METHODS**************************************************/

		protected function getClass(array $request, array $config, $authCodeRepoMock = null, $clientRepoMock = null, $strMock = null)
		{
			return new Lavoaster\OAuth2Server\Request\AuthorizationRequest($request, $config,
				$authCodeRepoMock ?: Mockery::mock('Lavoaster\OAuth2Server\Repositories\AuthorizationCodeRepositoryInterface'),
				$clientRepoMock ?: Mockery::mock('Lavoaster\OAuth2Server\Repositories\ClientRepositoryInterface'),
				$strMock ?: Mockery::mock('Illuminate\Support\Str')
			);
		}

		protected function getRequest(array $request = [])
		{
			$default = [
				'response_type' => 'code',
				'client_id'     => '1',
				'redirect_uri'  => 'http://localhost/',
				'scope'         => 'user',
				'state'         => 'login',
			];

			return array_merge($default, $request);
		}

		protected function getConfig($uri = true, $state = false, $scope = null)
		{
			return [
				'oauth' => [
					'enforce_redirect_uri' => $uri,  // Should the Redirect URI  always be present in the request
					'enforce_state'        => $state, // Should the state always be in the request
					'default_scope'        => $scope, // If no default is specified and the client doesn't send the scope parameter, the request must fail according to the OAuth2.0 RFC.
				],
				'authorization_code' => [
					'expiry' => '+1 Minute'
				]
			];
		}

		protected function getClient()
		{
			return Mockery::mock('Lavoaster\OAuth2Server\Storage\ClientInterface');
		}

		protected function assertEqualsArrays($expected, $actual, $message) {
			$this->assertTrue(count($expected) == count(array_intersect($expected, $actual)), $message);
		}
	}
}

namespace Lavoaster\OAuth2Server\Request {
	function strtotime($time) {
		return $time;
	}
}