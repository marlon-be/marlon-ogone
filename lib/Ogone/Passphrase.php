<?php
namespace Ogone;

/**
 * Ogone Passphrase Value Object
 */
class Passphrase 
{
	/**
	 * @var string
	 */
	private $passphrase;
	
	public function __construct($passphrase)
	{
		$this->passphrase = $passphrase;
	}
	
	/**
	 * String representation
	 */
	public function __toString()
	{
		return (string) $this->passphrase;
	}
}