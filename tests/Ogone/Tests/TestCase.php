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
use Ogone\Ecommerce\EcommercePaymentRequest;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /** @return EcommercePaymentRequest */
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

        // this field is mandatory in some european countries
        $paymentRequest->setSecure();

        return $paymentRequest;
    }
}
