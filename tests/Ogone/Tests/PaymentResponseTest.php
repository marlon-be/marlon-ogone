<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests;

use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\ShaComposer\ShaComposer;
use Ogone\PaymentResponse;

class PaymentResponseTest extends \TestCase
{
	/** @test */
	public function CanBeVerified()
	{
		$aRequest = $this->provideRequest();

		$paymentResponse = new PaymentResponse($aRequest);
		$this->assertTrue($paymentResponse->isValid(new FakeShaComposer));
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	*/
	public function CannotExistWithoutShaSign()
	{
		$paymentResponse = new PaymentResponse(array());
	}

	/** @test */
	public function ParametersCanBeRetrieved()
	{
		$aRequest = $this->provideRequest();

		$paymentResponse = new PaymentResponse($aRequest);
		$this->assertEquals($aRequest['orderID'], $paymentResponse->getParam('orderid'));
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function RequestIsFilteredFromNonOgoneParameters()
	{
		$aRequest = $this->provideRequest();

		$paymentResponse = new PaymentResponse($aRequest);
		$paymentResponse->getParam('unknown_param');
	}

	/** @test */
	public function ChecksStatus()
	{
		$aRequest = $this->provideRequest();

		$paymentResponse = new PaymentResponse($aRequest);
		$this->assertTrue($paymentResponse->isSuccessful());
	}

	/** @test */
	public function AmountIsConvertedToCent()
	{
		$aRequest = $this->provideRequest();

		$paymentResponse = new PaymentResponse($aRequest);
		$this->assertEquals(100, $paymentResponse->getParam('amount'));
	}

	public function provideFloats()
	{
		return array(
			array('17.89', 1789),
			array('65.35', 6535),
			array('12.99', 1299),
		);

	}

	/**
	 * @test
	 * @dataProvider provideFloats
	 */
	public function CorrectlyConvertsFloatAmountsToInteger($string, $integer)
	{
		$paymentResponse = new PaymentResponse(array('amount' => $string, 'shasign' => '123'));
		$this->assertEquals($integer, $paymentResponse->getParam('amount'));
	}

	/**
	 * Helper method to setup a request array
	 */
	private function provideRequest()
	{
		return array(
			'orderID' => '123',
			'SHASIGN' => FakeShaComposer::FAKESHASTRING,
			'UNKNOWN_PARAM' => false, /* unkown parameter, should be filtered out */
			'status' => PaymentResponse::STATUS_AUTHORISED,
			'amount' => 1,
		);
	}
}

