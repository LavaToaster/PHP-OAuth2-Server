<?php namespace Lavoaster\OAuth2Server\Storage;

interface StorageInterface
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
	 * @return mixed
	 */
	public function delete();
}