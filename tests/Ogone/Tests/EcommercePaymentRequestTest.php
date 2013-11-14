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
use Ogone\EcommercePaymentRequest;

class EcommercePaymentRequestTest extends \TestCase {

    /**
     * @test
     */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $paymentRequest = $this->provideEcommercePaymentRequest();
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
} 