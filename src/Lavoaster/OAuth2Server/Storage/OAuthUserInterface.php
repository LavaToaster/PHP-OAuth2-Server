<?php namespace Lavoaster\OAuth2Server\Storage;

interface OAuthUserInterface
{
    /**
     * Returns the user id
     *
     * @return int
     */
    public function getId();

    /**
     * Checks the password against the users password
     *
     * @param string $password
     * @return bool
     */
    public function checkPassword($password);
}