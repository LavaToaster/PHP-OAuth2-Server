<?php namespace Lavoaster\OAuth2Server\Storage;

use DB;
use Lavoaster\OAuth2Server\User\UserPasswordValidatorInterface;
use OAuth2\Storage\AccessTokenInterface;
use OAuth2\Storage\AuthorizationCodeInterface;
use OAuth2\Storage\ClientCredentialsInterface;
use OAuth2\Storage\JwtBearerInterface;
use OAuth2\Storage\RefreshTokenInterface;
use OAuth2\Storage\ScopeInterface;
use OAuth2\Storage\UserCredentialsInterface;

class Laravel implements AccessTokenInterface,
						 AuthorizationCodeInterface,
						 RefreshTokenInterface,
						 UserCredentialsInterface,
						 ClientCredentialsInterface,
						 ScopeInterface,
						 JwtBearerInterface
{

	protected $config;

	/**
	 * @var UserPasswordValidatorInterface
	 */
	protected $passwordValidator;

	public function __construct(array $config = [], UserPasswordValidatorInterface $passwordValidator)
	{
		$this->config = array_merge([
			'client_table'          => 'oauth_clients',
			'access_token_table'    => 'oauth_access_tokens',
			'refresh_token_table'   => 'oauth_refresh_tokens',
			'code_table'            => 'oauth_authorization_codes',
			'jwt_table'             => 'oauth_jwt'
		], $config);

		$this->passwordValidator = $passwordValidator;
	}

	public function getPassword()
	{
		return $this->passwordValidator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAccessToken($access_token)
	{
		$token = null;

		if ($query = DB::table($this->config['access_token_table'])->where('access_token', $access_token)->first()) {
			$token = (array) $query;
			$token['expires'] = strtotime($token['expires']);
		}

		return $token ?: false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setAccessToken($access_token, $client_id, $user_id, $expires, $scope = null)
	{
		$expires = date('Y-m-d H:i:s', $expires);
		$values = compact('access_token', 'client_id', 'user_id', 'expires', 'scope');

		$query = DB::table($this->config['access_token_table']);

		if ($this->getAccessToken($access_token)) {
			$result = (bool) $query->where('access_token', $access_token)->update($values);
		} else {
			$result = $query->insert($values);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthorizationCode($code)
	{
		$return = null;

		if ($query = DB::table($this->config['code_table'])->where('authorization_code', $code)->first()) {
			$return = (array) $query;
			$return['expires'] = strtotime($code['expires']);
		}

		return $return ?: false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setAuthorizationCode($authorization_code, $client_id, $user_id, $redirect_uri, $expires, $scope = null)
	{
		$expires = date('Y-m-d H:i:s', $expires);
		$values = compact('authorization_code', 'client_id', 'user_id', 'redirect_uri', 'expires', 'scope');

		$query = DB::table($this->config['code_table']);

		if ($this->getAuthorizationCode($authorization_code)) {
			$result = (bool) $query->where('authorization_code', $authorization_code)->update($values);
		} else {
			$result = $query->insert($values);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function expireAuthorizationCode($code)
	{
		return (bool) DB::table($this->config['code_table'])->where('authorization_code', $code)->delete();
	}

	/**
	 * {@inheritdoc}
	 */
	public function checkClientCredentials($client_id, $client_secret = null)
	{
		$client = $this->getClientDetails($client_id);

		return $client && $client['client_secret'] == $client_secret;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientDetails($client_id)
	{
		$client = (array) DB::table($this->config['client_table'])->where('client_id', $client_id)->first();

		return count($client) ? $client : false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function checkRestrictedGrantType($client_id, $grant_type)
	{
		$details = $this->getClientDetails($client_id);
		if (isset($details['grant_types'])) {
			$grant_types = explode(' ', $details['grant_types']);

			return in_array($grant_type, (array) $grant_types);
		}

		// if grant_types are not defined, then none are restricted
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRefreshToken($refresh_token)
	{
		$token = null;

		if ($query = DB::table($this->config['refresh_token_table'])->where('refresh_token', $refresh_token)->first()) {
			$token = (array) $query;
			$token['expires'] = strtotime($token['expires']);
		}

		return $token ?: false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setRefreshToken($refresh_token, $client_id, $user_id, $expires, $scope = null)
	{
		$expires = date('Y-m-d H:i:s', $expires);
		$values = compact('refresh_token', 'client_id', 'user_id', 'expires', 'scope');

		$query = DB::table($this->config['refresh_token_table']);

		if ($this->getRefreshToken($refresh_token)) {
			$result = (bool) $query->where('refresh_token', $refresh_token)->update($values);
		} else {
			$result = $query->insert($values);
		}

		return $result;
	}

	/**
	 * {@inheritdoc}
	 */
	public function unsetRefreshToken($refresh_token)
	{
		return (bool) DB::table($this->config['refresh_token_table'])->where('refresh_token', $refresh_token)->delete();
	}

	/**
	 * {@inheritdoc}
	 */
	public function scopeExists($scope, $client_id = null)
	{
		if (!is_null($client_id)) {
			$query = DB::table($this->config['client_table'])->where('client_id', $client_id)->first(['supported_scopes']);
			$clientSupportedScopes = explode(' ', $query->supported_scopes);
			$scope = explode(' ', $scope);

			return (count(array_diff($scope, $clientSupportedScopes)) == 0);
		}
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDefaultScope($client_id = null)
	{
		if (!is_null($client_id)) {
			$query = DB::table($this->config['client_table'])->where('client_id', $client_id)->first(['default_scope']);

			return $query->default_scope;
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function checkUserCredentials($username, $password)
	{
		$user = $this->getUserDetails($username);

		return $this->config['password_validator']($user, $password);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUserDetails($username)
	{
		$query = (array) DB::table($this->config['user_table'])->where($this->config['user_attribute'], $username)->first();

		if(!$query) return false;

		return array_merge([
				'user_id' => $query[$this->config['user_id']]
		], $query);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientKey($client_id, $subject)
	{
		$query = DB::table($this->config['jwt_table'])->where('client_id', $client_id)->where('subject', $subject)->first('public_key');

		return $query->public_key ?: false;
	}
}