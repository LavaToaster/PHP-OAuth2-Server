<?php namespace Lavoaster\OAuth2Server\User;

interface UserPasswordValidatorInterface
{
	public function checkPassword(array $user, $password);
}