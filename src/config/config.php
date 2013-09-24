<?php

/* TODO: Expand on descriptions and format them to laravel style */

return [
	'access_token' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\AccessToken', // Class that implements the access token interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\AccessTokenRepository' // Class that implements the access token repository interface
	],

	'refresh_token' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\RefreshToken', // Class that implements the refresh token interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\RefreshTokenRepository' // Class that implements the refresh token repository interface
	],

	'authorization_code' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\AuthorizationCode', // Class that implements the authorization code interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\AuthorizationCodeRepository' // Class that implements the authorization code repository interface
	],

	'client' => [
		'storage' => 'Lavoaster\OAuth2Server\Storage\Eloquent\Client', // Class that implements the client interface
		'repository' => 'Lavoaster\OAuth2Server\Repositories\Eloquent\ClientRepository' // Class that implements the client repository interface
	],

	'user' => [
		'id' => 'id', // Column that stores the user id
		'table' => 'users', // Table that contains the users
		'identifier' => 'username', // Column to use when identifying users through the credentials grant type
		'class' => 'User' // Class that implements OAuthUserInterface so the library can query your user table
	]
];