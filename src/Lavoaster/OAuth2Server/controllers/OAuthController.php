<?php namespace Lavoaster\OAuth2Server\Controllers;

use Illuminate\Routing\Controllers\Controller;
use Lavoaster\OAuth2Server\OAuth2Server;

class OAuth2Controller extends Controller
{
	protected $server;

	public function __construct(OAuth2Server $server)
	{
		$this->server = $server;
	}

	public function postToken()
	{
		return $this->server->handleTokenRequest();
	}

	public function getAuthorize()
	{
		return \View::make('authorize'); // Sorry, you'll have to manually create this right now
	}

	public function postAuthorize()
	{
		if (\Auth::attempt(array('username' => \Input::input('username'), 'password' => \Input::input('password')))) {
			return $this->server->handleAuthorizeRequest((bool) \Input::input('authorized', 0), \Auth::user()->id);
		}

		return \Redirect::to('/oauth2/authorize');
	}

	public function getTest()
	{
		//Should be turned into a before filter in the future
		if (!$this->server->verifyResourceRequest()) {
			return $this->server->getResponse();
		}
		// Valid OAuthToken, may proceed as like normal

		var_dump($this->server->verifyResourceRequest());

		return ['error' => false, 'data' => 'You won the OAuth2 Game'];
	}
}