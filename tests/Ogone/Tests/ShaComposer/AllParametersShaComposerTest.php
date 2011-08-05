<?php
namespace Ogone\Tests\ShaComposer;

use Ogone\Passphrase;
use Ogone\ShaComposer\AllParametersShaComposer;

class AllParametersShaComposerTest extends \TestCase
{
	/**
	 * @test
	 * @dataProvider provideRequest
	 */
	public function ShaStringIsComposedCorrectly(PassPhrase $passphrase, array $request, $expectedSha)
	{
		$composer = new AllParametersShaComposer($passphrase);
		$this->assertEquals($expectedSha, $composer->compose($request));
	}

	public function provideRequest()
	{
		// @todo also test unknown params, empty params

		$passphrase = new PassPhrase('Mysecretsig1875!?');

		$expectedSha1 = 'B209960D5703DD1047F95A0F97655FFE5AC8BD52';
		$request1 = array(
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

		$expectedSha2 = 'D58400479DCEDD6B6C7E67D61FDC0CC9E6ED65CB';
		$request2 = array (
			'orderID' => 'myorderid1678834094',
			'currency' => 'EUR',
			'amount' => '99',
			'PM' => 'CreditCard',
			'ACCEPTANCE' => 'test123',
			'STATUS' => '9',
			'CARDNO' => 'XXXXXXXXXXXX1111',
			'ED' => '0312',
			'CN' => 'Some Name',
			'TRXDATE' => '01/10/11',
			'PAYID' => '9126297',
			'NCERROR' => '0',
			'BRAND' => 'VISA',
			'COMPLUS' => 'my feedbackmessage',
			'IP' => '12.123.12.123',
		);

		return array(
			array($passphrase, $request1, $expectedSha1),
			array($passphrase, $request2, $expectedSha2),
		);

	}


}