<?php namespace Lavoaster\OAuth2Server\User;

class LaravelUserPasswordValidator implements UserPasswordValidatorInterface
{
	public function checkPassword(array $user, $password)
	{
		return \Hash::check($password, $user['password']);
	}
}