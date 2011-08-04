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

	/** @@codeCoverageIgnore */
	public function __construct($passphrase)
	{
		if(!is_string($passphrase)) {
			throw new \InvalidArgumentException("String expected");
		}
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