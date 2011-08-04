<?php
namespace Ogone\Tests\ShaComposer;

use Ogone\Passphrase;
use Ogone\ShaComposer\AllParametersShaComposer;

class AllParametersShaComposerTest extends \TestCase
{
	const PASSPHRASE = 'Mysecretsig1875!?';
	const SHASTRING = 'B209960D5703DD1047F95A0F97655FFE5AC8BD52';

	/** @test */
	public function ShaStringCanBeComposed()
	{
		$aRequest = $this->provideRequest();

		$composer = new AllParametersShaComposer(new Passphrase(self::PASSPHRASE));
		$shaString = $composer->compose($aRequest);

		$this->assertEquals(self::SHASTRING, $shaString);
	}

	private function provideRequest()
	{
		return array(
			'currency' => 'EUR',
			'ACCEPTANCE' => 1234,
			'amount' => 15,
			'BRAND' => 'VISA',
			'CARDNO' => 'xxxxxxxxxxxx1111',
			'NCERROR' => 0,
			'PAYID' => 32100123,
			'PM' => 'CreditCard',
			'STATUS' => 9,
			'orderID' => 12
		);
	}
}