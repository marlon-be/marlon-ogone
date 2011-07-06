<?php
namespace Ogone\Tests\ShaComposer;

use Ogone\Passphrase;
use Ogone\ShaComposer\MainParametersShaComposer;

class MainParametersShaComposerTest extends \TestCase
{
	const PASSPHRASE = 'm0b1l4sha!';
	const SHASTRING = 'C17C595E20FD2BAC4AFFA68E677DA34F43023249';
	
	/** @test */
	public function ShaStringCanBeComposed()
	{
		$request = $this->setupRequest();
		
		$composer = new MainParametersShaComposer(new Passphrase(self::PASSPHRASE));
		$shaString = $composer->compose($request);
		
		$this->assertEquals(self::SHASTRING, $shaString);
	}
		
	private function setupRequest()
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