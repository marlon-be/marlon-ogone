<?php
namespace Ogone\ShaComposer;

use Ogone\Passphrase;

/**
 * Base class for SHA Composers
 */
abstract class AbstractShaComposer
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

	/**
	 * Compose SHA string based on Ogone request parameters
	 * @param array $requestParameters
	 */
	public abstract function compose($requestParameters);
}