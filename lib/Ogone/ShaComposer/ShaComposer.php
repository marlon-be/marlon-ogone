<?php
namespace Ogone\ShaComposer;

use Ogone\Passphrase;

/**
 * SHA Composers interface
 */
interface ShaComposer
{
	/**
	 * Compose SHA string based on Ogone request parameters
	 * @param array $requestParameters
	 */
	public function compose($requestParameters);
}