<?php

/* TODO: Expand on descriptions and format them to laravel style */

return [
	'user' => [
		'id' => 'id', // Column that stores the user id
		'table' => 'users', // Table that contains the users
		'identifier' => 'username', // Column to use when identifying users through the credentials grant type
		'class' => 'User' // Class that implements OAuthUserInterface so the library can query your user table
	]
];