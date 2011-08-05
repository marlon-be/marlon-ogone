<?php

use Ogone\PaymentRequest;
require_once 'PHPUnit/Framework/TestCase.php';

abstract class TestCase extends PHPUnit_Framework_TestCase
{
	/** @return PaymentRequest Only the required fields are filled in */
	protected function providePaymentRequest()
	{
		$paymentRequest = new PaymentRequest;

		$paymentRequest->setPspid("123456789");
		$paymentRequest->setOrderid("987654321");

		$paymentRequest->setCustomername("Louis XIV");
		$paymentRequest->setOwnerAddress("1, Rue du Palais");
		$paymentRequest->setOwnerTown("Versailles");
		$paymentRequest->setOwnerZip('2300');
		$paymentRequest->setOwnerCountry("FR");
		$paymentRequest->setEmail("louis.xiv@versailles.fr");


		$paymentRequest->setAmount(100);

		return $paymentRequest;
	}
}