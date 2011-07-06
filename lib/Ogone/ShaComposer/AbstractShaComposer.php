<?php
namespace Ogone\ShaComposer;

use Ogone\Passphrase;

/**
 * Base class for SHA Composers
 */
abstract class AbstractShaComposer implements ShaComposer
{
	/**
	 * @var Passphrase
	 */
	protected $passphrase;
	
	/**
	 * @param string $passphrase
	 */
	public function __construct(Passphrase $passphrase)
	{
		$this->passphrase = $passphrase;
	}

}