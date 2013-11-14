<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\Passphrase;
use Ogone\ParameterFilter\ShaInParameterFilter;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\PaymentRequest;
use Ogone\EcommercePaymentRequest;
use Ogone\CreateAliasRequest;

require_once 'PHPUnit/Framework/TestCase.php';
require_once __DIR__.'/Ogone/Tests/ShaComposer/FakeShaComposer.php';

abstract class TestCase extends PHPUnit_Framework_TestCase
{
	/** @return EcommercePaymentRequest*/
	protected function provideMinimalPaymentRequest()
	{
        $paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
        $paymentRequest->setPspid('123456789');
        $paymentRequest->setOrderid('987654321');
		$paymentRequest->setOgoneUri(EcommercePaymentRequest::TEST);

		// minimal required fields for ogone (together with pspid and orderid)
		$paymentRequest->setCurrency("EUR");
		$paymentRequest->setAmount(100);

		// these fields are actually optional but are good practice to be included
		$paymentRequest->setCustomername("Louis XIV");
		$paymentRequest->setOwnerAddress("1, Rue du Palais");
		$paymentRequest->setOwnerTown("Versailles");
		$paymentRequest->setOwnerZip('2300');
		$paymentRequest->setOwnerCountry("FR");
		$paymentRequest->setEmail("louis.xiv@versailles.fr");

		return $paymentRequest;
	}

	/** @return EcommercePaymentRequest*/
	protected function provideCompletePaymentRequest()
	{

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
		$paymentRequest->setParamvar('aParamVar');
		$paymentRequest->setOrderDescription("Four horses and a carriage");

		$paymentRequest->setOwnerPhone('123456789');

		return $paymentRequest;
	}

    /** @return EcommercePaymentRequest*/
    protected function provideEcommercePaymentRequest()
    {
        $ecommercePaymentRequest = new EcommercePaymentRequest(new FakeShaComposer());
        $ecommercePaymentRequest->setPspid('123456789');
        $ecommercePaymentRequest->setOrderid('987654321');
        $ecommercePaymentRequest->setCurrency('EUR');
        $ecommercePaymentRequest->setAmount(100);

        return $ecommercePaymentRequest;
    }

    /** @return CreateAliasRequest*/
    protected function provideMinimalAliasRequest()
    {
        $aliasRequest = new CreateAliasRequest(new FakeShaComposer);
        $aliasRequest->setPspid('18457454');
        $aliasRequest->setAccepturl('http://example.com/accept');
        $aliasRequest->setExceptionurl('http://example.com/exception');

        return $aliasRequest;
    }

}