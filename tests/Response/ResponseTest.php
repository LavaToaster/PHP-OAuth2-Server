<?php

class ResponseTest extends PHPUnit_Framework_Testcase
{
	public function testErrorContentIsSet()
	{
		$responseClass = $this->getClass();
		$responseClass->setError(400, 'invalid_request', 'redirect_uri mismatch', 'http://tools.ietf.org/html/rfc6749#section-4.1.3');

		$error = [
			'error'             => 'invalid_request',
			'error_description' => 'redirect_uri mismatch',
			'error_uri'         => 'http://tools.ietf.org/html/rfc6749#section-4.1.3'
		];

		$response = $responseClass->get();

		$this->assertEquals(400, $response['status']);
		$this->assertEqualsArrays($error, $response['content'], 'Failed asserting ' . print_r($error, true) . ' was equal to ' . print_r($response['content'], true) . ' in ' . print_r($response, true));
	}

	public function testErrorRedirectIsSet()
	{
		$responseClass = $this->getClass();
		$responseClass->setRequestDetails(['redirect_uri' => 'http://localhost/']);
		$responseClass->setError(302, 'invalid_scope', 'The requested scope is invalid, unknown, or malformed');

		$response = $responseClass->get();

		$this->assertEquals(302 ,$response['status']);
		$this->assertEquals('http://localhost/?error=invalid_scope&error_description=' . urlencode('The requested scope is invalid, unknown, or malformed'), $response['redirect_uri']);
	}

	public function testSuccess()
	{
		$tokenContent = [
			'access_token' => 'access',
			'token_type' => 'bearer',
			'expires_in' => 3600,
			'refresh_token' => 'refresh',
		];

		$responseClass = $this->getClass();
		$responseClass->setContent($tokenContent);

		$response = $responseClass->get();

		$this->assertEqualsArrays($tokenContent, $response['content']);
	}

	/*****************************************HELPER METHODS**************************************************/

	public function getClass()
	{
		return new \Lavoaster\OAuth2Server\Response\Response();
	}

	protected function assertEqualsArrays($expected, $actual, $message = '') {
		$this->assertTrue(count($expected) == count(array_intersect($expected, $actual)), $message);
	}
}