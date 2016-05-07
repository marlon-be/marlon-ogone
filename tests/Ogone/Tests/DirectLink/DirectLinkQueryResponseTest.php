<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\Tests\DirectLink;

use Ogone\DirectLink\DirectLinkQueryResponse;

class DirectLinkQueryResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function CantExistWithoutXmlFile()
    {
        $queryResponse = new DirectLinkQueryResponse('');
    }

    /** @test */
    public function ParametersCanBeRetrieved()
    {
        $xml = $this->provideXML();

        $queryResponse = new DirectLinkQueryResponse($xml);
        $this->assertEquals('5', $queryResponse->getParam('orderid'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function RequestIsFilteredFromNonOgoneParameters()
    {
        $xml = $this->provideXML();

        $queryResponse = new DirectLinkQueryResponse($xml);
        $queryResponse->getParam('unknown_param');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function ChecksInvalidXml()
    {
        $xml = $this->provideInvalidXML();

        $queryResponse = new DirectLinkQueryResponse($xml);
    }

    /** @test */
    public function ChecksStatus()
    {
        $xml = $this->provideXML();

        $queryResponse = new DirectLinkQueryResponse($xml);
        $this->assertTrue($queryResponse->isSuccessful());
    }

    /** @test */
    public function AmountIsConvertedToCent()
    {
        $xml = $this->provideXML();

        $queryResponse = new DirectLinkQueryResponse($xml);
        $this->assertEquals(450, $queryResponse->getParam('amount'));
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

        $queryResponse = new DirectLinkQueryResponse($xml);
        $this->assertEquals($integer, $queryResponse->getParam('amount'));
    }

    /**
     * Helper method to setup an xml-string
     */
    private function provideXML($amount = null)
    {

        $xml = '<?xml version="1.0"?>
                <ncresponse
                orderID="5"
                PAYID="33146134"
                PAYIDSUB=""
                NCSTATUS="0"
                NCERROR="0"
                NCERRORPLUS="!"
                ACCEPTANCE="test123"
                STATUS="91"
                ECI="7"
                AMOUNT="'.(($amount) ? $amount : '4.5').'"
                CURRENCY="GBP"
                PM="CreditCard"
                BRAND="VISA"
                CARDNO="XXXXXXXXXXXX1111"
                IP="127.0.0.1">
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
