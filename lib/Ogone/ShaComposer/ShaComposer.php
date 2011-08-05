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
	 * @param array $parameters
	 */
	public function compose(array $parameters);
}