<?php

use Ogone\PaymentRequest;
require_once 'PHPUnit/Framework/TestCase.php';

abstract class TestCase extends PHPUnit_Framework_TestCase
{
	/** @return PaymentRequest*/
	protected function provideMinimalPaymentRequest()
	{
		$paymentRequest = new PaymentRequest;

		$paymentRequest->setOgoneUri(PaymentRequest::TEST);
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

	/** @return PaymentRequest*/
	protected function provideCompletePaymentRequest()
	{
		/** @return PaymentRequest */
		$paymentRequest = $this->provideMinimalPaymentRequest();

		$paymentRequest->setAccepturl('http://example.com/accept');
		$paymentRequest->setDeclineurl('http://example.com/decline');
		$paymentRequest->setExceptionurl('http://example.com/exception');
		$paymentRequest->setCancelurl('http://example.com/cancel');
		$paymentRequest->setDynamicTemplateUri('http://example.com/template');

		$paymentRequest->setCurrency('EUR');
		$paymentRequest->setLanguage('nl_BE');
		$paymentRequest->setPaymentMethod('CreditCard');
		$paymentRequest->setBrand('VISA');

		$paymentRequest->setFeedbackMessage("Thanks for ordering");
		$paymentRequest->setFeedbackParams(array('amountOfProducts' => '5', 'usedCoupon' => 1));
		$paymentRequest->setOrderDescription("Four horses and a carriage");

		$paymentRequest->setOwnerPhone('123456789');

		return $paymentRequest;
	}

}