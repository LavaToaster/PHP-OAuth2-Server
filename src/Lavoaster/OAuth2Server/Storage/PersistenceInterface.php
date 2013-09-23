<?php namespace Lavoaster\OAuth2Server\Storage;

interface PersistenceInterface
{
	/**
	 * Saves the resource
	 *
	 * @return void
	 */
	public function save();

	/**
	 * Deletes the resource
	 *
	 * @return void
	 */
	public function delete();
}