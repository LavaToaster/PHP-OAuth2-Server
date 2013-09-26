<?php namespace Lavoaster\OAuth2Server\Response;

class Response
{
	protected $status = 200;
	protected $parameters = [];
	protected $content = [];
	protected $requestDetails = [];
	protected $headers = [];

	/**
	 * Sets an error, based on status  it will decide if it needs to redirect back to the client
	 *
	 * @param int $status
	 * @param string $error
	 * @param string $error_description
	 * @param string $error_uri
	 */
	public function setError($status, $error, $error_description = '', $error_uri = '')
	{
		$this->status = $status;
		$error_array = ['error' => $error];

		if(!empty($error_description)) $error_array['error_description'] = $error_description;
		if(!empty($error_uri)) $error_array['error_uri'] = $error_uri;

		if($status >= 300 && $status < 400) {
			$this->parameters = $error_array;
		} else {
			$this->content = $error_array;
		}
	}

	public function setRequestDetails(array $requestDetails)
	{
		$this->requestDetails = $requestDetails;
	}

	public function get()
	{
		$response = [
			'response' => 'output',
			'status'   => $this->status
		];

		if($this->status >= 300 && $this->status < 400) {
			$response['response']     = 'redirect';
			$response['redirect_uri'] = $this->requestDetails['redirect_uri'] . "?" . http_build_query($this->parameters);
		} else {
			$response['content'] = $this->content;
		}

		return $response;
	}

	public function setContent(array $content)
	{
		$this->content = $content;
	}
}