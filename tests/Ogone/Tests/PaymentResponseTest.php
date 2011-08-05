<?php
namespace Ogone\Tests;

use Ogone\ShaComposer\ShaComposer;
use Ogone\PaymentResponse;

class PaymentResponseTest extends \TestCase
{
	const SHASTRING = 'foo';

	/** @test */
	public function CanBeVerified()
	{
		$aRequest = $this->provideRequest();

		$paymentResponse = new PaymentResponse($aRequest);
		$this->assertTrue($paymentResponse->isValid(new FakeShaComposer()));
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

	/**
	 * Helper method to setup a request array
	 */
	private function provideRequest()
	{
		return array(
			'orderID' => '123',
			'SHASIGN' => self::SHASTRING,
			'UNKNOWN_PARAM' => false, /* unkown parameter, should be filtered out */
			'status' => 5,
		);
	}
}

/**
 * Fake SHA Composer to decouple test from actual SHA composers
 */
class FakeShaComposer implements ShaComposer
{
	public function compose(array $responseParameters)
	{
		return PaymentResponseTest::SHASTRING;
	}
}