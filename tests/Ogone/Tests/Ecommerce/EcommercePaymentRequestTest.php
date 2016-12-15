<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests\Ecommerce;

use Ogone\DirectLink\PaymentOperation;
use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\Ecommerce\EcommercePaymentRequest;
use Ogone\Tests\TestCase;

class EcommercePaymentRequestTest extends TestCase
{
    /** @test */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $paymentRequest = $this->provideMinimalPaymentRequest();
        $paymentRequest->validate();
    }

    /** @test */
    public function IsValidWhenAllFieldsAreFilledIn()
    {
        $paymentRequest = $this->provideCompletePaymentRequest();
        $paymentRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
        $paymentRequest->validate();
    }

    /** @test */
    public function UnimportantParamsUseMagicSetters()
    {
        $paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
        $paymentRequest->setBgcolor('FFFFFF');
        $this->assertEquals('FFFFFF', $paymentRequest->getBgcolor());
    }

    /**
     * @test
     * @dataProvider provideBadParameters
     * @expectedException \InvalidArgumentException
     */
    public function BadParametersCauseExceptions($method, $value)
    {
        $paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
        $paymentRequest->$method($value);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     */
    public function UnknownMethodFails()
    {
        $paymentRequest = new EcommercePaymentRequest(new FakeShaComposer);
        $paymentRequest->getFoobar();
    }

    public function provideBadParameters()
    {
        $longString = str_repeat('longstring', 100);
        $notAUri = 'http://not a uri';
        $longUri = "http://www.example.com/$longString";

        return array(
            array('setAccepturl', $notAUri),
            array('setAmount', 10.50),
            array('setAmount', -1),
            array('setAmount', 1000000000000000),
            array('setBrand', 'Oxfam'),
            array('setCancelurl', $notAUri),
            array('setCancelurl', $longUri),
            array('setCurrency', 'Belgische Frank'),
            //array('setCustomername', ''),
            array('setDeclineurl', $notAUri),
            array('setDynamicTemplateUri', $notAUri),
            array('setEmail', 'foo @ bar'),
            array('setEmail', "$longString@example.com"),
            array('setExceptionurl', $notAUri),
            //array('setFeedbackMessage', ''),
            //array('setFeedbackParams', ''),
            array('setLanguage', 'West-Vlaams'),
            array('setOgoneUri', $notAUri),
            array('setOrderDescription', $longString),
            array('setOrderid', "Weird çh@®a©†€rs"),
            array('setOrderid', $longString),
            array('setOwnerAddress', $longString),
            array('setOwnercountry', 'Benidorm'),
            array('setOwnerPhone', $longString),
            array('setOwnerTown', $longString),
            array('setOwnerZip', $longString),
            array('setParamvar', $longString),
            array('setPaymentMethod', 'Digital'),
            array('setPspid', $longString),
        );
    }

    /** @return EcommercePaymentRequest */
    private function provideCompletePaymentRequest()
    {
        $paymentRequest = $this->provideMinimalPaymentRequest();

        $paymentRequest->setAccepturl('http://example.com/accept');
        $paymentRequest->setDeclineurl('http://example.com/decline');
        $paymentRequest->setExceptionurl('http://example.com/exception');
        $paymentRequest->setCancelurl('http://example.com/cancel');
        $paymentRequest->setBackurl('http://example.com/back');
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

        $paymentRequest->setOperation(new PaymentOperation(PaymentOperation::REQUEST_FOR_DIRECT_SALE));

        return $paymentRequest;
    }
}
