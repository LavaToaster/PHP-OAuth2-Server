<?php namespace Lavoaster\OAuth2Server\HttpBridge;

use OAuth2\ResponseInterface;
use Illuminate\Http\JsonResponse;

class Response extends JsonResponse implements ResponseInterface
{
	public function addParameters(array $parameters)
	{
		// if there are existing parametes, add to them
		if ($this->content && $data = json_decode($this->content, true)) {
			$parameters = array_merge($data, $parameters);
		}

		// this will encode the php array as json data
		$this->setData($parameters);
	}

	public function addHttpHeaders(array $httpHeaders)
	{
		foreach ($httpHeaders as $key => $value) {
			$this->headers->set($key, $value);
		}
	}

	public function getParameter($name)
	{
		if ($this->content && $data = json_decode($this->content, true)) {
			return isset($data[$name]) ? $data[$name] : null;
		}
	}

	public function setError($statusCode, $name, $description = null, $uri = null)
	{
		$this->setStatusCode($statusCode);
		$this->addParameters(array_filter(array(
			'error' => $name,
			'error_description' => $description,
			'error_uri' => $uri,
		)));
	}

	public function setRedirect($statusCode = 302, $url, $state = null, $error = null, $errorDescription = null, $errorUri = null)
	{
		$this->setStatusCode($statusCode);
		$this->addParameters(array_filter(array(
			'error' => $error,
			'error_description' => $errorDescription,
			'error_uri' => $errorUri,
		)));

		$this->headers->set('Location', $url);
	}
}
