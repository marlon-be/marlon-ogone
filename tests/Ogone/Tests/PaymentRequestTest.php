<?php
namespace Ogone\Tests;

use Ogone\PaymentRequest;

class PaymentRequestTest extends \TestCase
{
	/** @test */
	public function IsValidWhenRequiredFieldsAreFilledIn()
	{
		$paymentRequest = $this->providePaymentRequest();
		$paymentRequest->validate();
	}

	/**
	 * @test
	 * @expectedException \RuntimeException
	 */
	public function IsInvalidWhenFieldsAreMissing()
	{
		$paymentRequest = new PaymentRequest;
		$paymentRequest->validate();
	}
}