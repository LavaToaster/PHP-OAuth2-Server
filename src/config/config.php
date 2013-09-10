<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Grant Types
	|--------------------------------------------------------------------------
	|
	| Specify the grant types you would like to be available for use
	|
	| Available:
	|   - authorization_code
	|   - client_credentials
	|   - refresh_token
	|   - password
	|   - urn:ietf:params:oauth:grant-type:jwt-bearer
	|
	*/

	'grant_types' => [
		'authorization_code',
		'client_credentials',
		'refresh_token'
	],
	
		/*
	|--------------------------------------------------------------------------
	| Routing
	|--------------------------------------------------------------------------
	|
	| Define the route options, currently you can only set the controller 
	| being used
	|
	|
	*/

	'routing' => [

		'controller' => 'Lavoaster\OAuth2Server\Controllers\OAuth2Controller'

	],

	/*
	|--------------------------------------------------------------------------
	| Users
	|--------------------------------------------------------------------------
	|
	| Configuration specific for utilising the users table.
	|
	*/

	'users' => [

		/*
		|--------------------------------------------------------------------------
		| Table
		|--------------------------------------------------------------------------
		|
		| Specify the table in which you store your users in.
		|
		*/

		'table' => 'users',

		/*
		|--------------------------------------------------------------------------
		| User ID
		|--------------------------------------------------------------------------
		|
		| Specify what column the user id is stored in.
		|
		*/

		'user_id' => 'id',

		/*
		|--------------------------------------------------------------------------
		| Login Attribute
		|--------------------------------------------------------------------------
		|
		| Specify what column is used to identify the user. Do they login with
		| their username or email? etc...
		|
		*/

		'login_attribute' => 'username',
		
		/*
		|--------------------------------------------------------------------------
		| Password Validation Class
		|--------------------------------------------------------------------------
		|
		| Specify what class should be used to validate the users password
		|
		*/
		
		'password_validator' => 'Lavoaster\OAuth2Server\User\LaravelUserPasswordValidator'

	],

];