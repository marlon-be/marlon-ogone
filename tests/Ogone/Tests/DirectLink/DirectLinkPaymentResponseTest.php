<?php
/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests\DirectLink;

use Ogone\DirectLink\DirectLinkPaymentResponse;

class DirectLinkPaymentResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function CantExistWithoutXmlFile()
    {
        $paymentResponse = new DirectLinkPaymentResponse('');
    }

    /** @test */
    public function ParametersCanBeRetrieved()
    {
        $xml = $this->provideXML();

        $paymentResponse = new DirectLinkPaymentResponse($xml);
        $this->assertEquals('123', $paymentResponse->getParam('orderid'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function RequestIsFilteredFromNonOgoneParameters()
    {
        $xml = $this->provideXML();

        $paymentResponse = new DirectLinkPaymentResponse($xml);
        $paymentResponse->getParam('unknown_param');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function ChecksInvalidXml()
    {
        $xml = $this->provideInvalidXML();

        $paymentResponse = new DirectLinkPaymentResponse($xml);
    }

    /** @test */
    public function ChecksStatus()
    {
        $xml = $this->provideXML();

        $paymentResponse = new DirectLinkPaymentResponse($xml);
        $this->assertTrue($paymentResponse->isSuccessful());
    }

    /** @test */
    public function AmountIsConvertedToCent()
    {
        $xml = $this->provideXML();

        $paymentResponse = new DirectLinkPaymentResponse($xml);
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
        $xml = $this->provideXML($string);

        $paymentResponse = new DirectLinkPaymentResponse($xml);
        $this->assertEquals($integer, $paymentResponse->getParam('amount'));
    }

    /**
     * Helper method to setup an xml-string
     */
    private function provideXML($amount = null)
    {

        $xml = '<?xml version="1.0"?>
                <ncresponse

                ORDERID="123"
                PAYID="0"
                NCSTATUS="5"
                NCERROR=""
                ACCEPTANCE=""
                STATUS="5"
                AMOUNT="'.(($amount) ? $amount : '1').'"
                CURRENCY="EUR"
                PM=""
                BRAND=""
                NCERRORPLUS="">
                </ncresponse>';

        return $xml;
    }

    /**
     * Helper method to setup an invalid xml-string
     */
    private function provideInvalidXML()
    {
        $xml = '<?xml version="1.0"?>
                <ncresponse
                </ncresponse>';

        return $xml;
    }
}
