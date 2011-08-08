<?php
namespace Ogone\Tests\ShaComposer;

use Ogone\ShaComposer\ShaComposer;

/**
 * Fake SHA Composer to decouple test from actual SHA composers
 */
class FakeShaComposer implements ShaComposer
{
	const FAKESHASTRING = 'foo';

	public function compose(array $responseParameters)
	{
		return self::FAKESHASTRING;
	}
}