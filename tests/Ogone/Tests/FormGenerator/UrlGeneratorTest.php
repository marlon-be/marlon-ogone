<?php

namespace Ogone\Tests\FormGenerator;

use Ogone\Tests\TestCase;
use Ogone\Ecommerce\EcommercePaymentRequest;
use Ogone\FormGenerator\UrlGenerator;
use Ogone\PaymentRequest;
use Ogone\Tests\ShaComposer\FakeShaComposer;

class UrlGeneratorTest extends TestCase
{

    /** @test */
    public function GeneratesAnUrl()
    {
        $expected = EcommercePaymentRequest::TEST . '?'.
            'pspid=123456789' . '&'.
            'orderid=987654321' . '&'.
            'currency=EUR' . '&'.
            'amount=100' . '&'.
            'cn=Louis+XIV' . '&'.
            'owneraddress=1%2C+Rue+du+Palais' . '&'.
            'ownertown=Versailles' . '&'.
            'ownerzip=2300' . '&'.
            'ownercty=FR' . '&'.
            'email=louis.xiv%40versailles.fr' . '&'.
            'win3ds=mainw' . '&'.
            PaymentRequest::SHASIGN_FIELD . '=' . FakeShaComposer::FAKESHASTRING;

        $paymentRequest = $this->provideMinimalPaymentRequest();

        $urlGenerator = new UrlGenerator();
        $url = $urlGenerator->render($paymentRequest);

        $this->assertEquals(strtolower($expected), strtolower($url));
    }
}
