<?php
namespace Ogone\ShaComposer;

use Ogone\Passphrase;

/**
 * SHA Composers interface
 */
interface ShaComposer
{
	/**
	 * Compose SHA string based on Ogone response parameters
	 * @param array $responseParameters
	 */
	public function compose(array $responseParameters);
}