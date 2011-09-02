<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests\ShaComposer;

use Ogone\Passphrase;
use Ogone\ShaComposer\LegacyShaComposer;

class LegacyShaComposerTest extends \TestCase
{
	const PASSPHRASE = 'passphrase-set-in-ogone-interface';
	const SHASTRING = '66BF34D8B3EF2136E0C267BDBC1F708B8D75A8AA';

	/** @test */
	public function ShaStringCanBeComposed()
	{
		$aRequest = $this->provideRequest();

		$composer = new LegacyShaComposer(new Passphrase(self::PASSPHRASE));
		$shaString = $composer->compose($aRequest);

		$this->assertEquals(self::SHASTRING, $shaString);
	}

	private function provideRequest()
	{
		return array(
			'ACCEPTANCE' => 'test123',
			'AMOUNT' => '19.08',
			'BRAND' => 'VISA',
			'CARDNO' => 'XXXXXXXXXXXX1111',
			'CN' => 'Marlon',
			'CURRENCY' => 'EUR',
			'ED' => '0113',
			'IP' => '81.82.214.142',
			'NCERROR' => 0,
			'ORDERID' => 2101947639,
			'PAYID' => 10673859,
			'PM' => 'CreditCard',
			'STATUS' => 5,
			'TRXDATE' => '07/05/11'
		);
	}
}