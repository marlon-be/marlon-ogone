<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\Tests\DirectLink;

use Ogone\DirectLink\DirectLinkMaintenanceResponse;

class DirectLinkMaintenanceResponseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function CantExistWithoutXmlFile()
    {
        $maintenanceResponse = new DirectLinkMaintenanceResponse('');
    }

    /** @test */
    public function ParametersCanBeRetrieved()
    {
        $xml = $this->provideXML();

        $maintenanceResponse = new DirectLinkMaintenanceResponse($xml);
        $this->assertEquals('5', $maintenanceResponse->getParam('orderid'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function RequestIsFilteredFromNonOgoneParameters()
    {
        $xml = $this->provideXML();

        $maintenanceResponse = new DirectLinkMaintenanceResponse($xml);
        $maintenanceResponse->getParam('unknown_param');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function ChecksInvalidXml()
    {
        $xml = $this->provideInvalidXML();

        $maintenanceResponse = new DirectLinkMaintenanceResponse($xml);
    }

    /** @test */
    public function ChecksStatus()
    {
        $xml = $this->provideXML();

        $maintenanceResponse = new DirectLinkMaintenanceResponse($xml);
        $this->assertTrue($maintenanceResponse->isSuccessful());
    }

    /** @test */
    public function AmountIsConvertedToCent()
    {
        $xml = $this->provideXML();

        $maintenanceResponse = new DirectLinkMaintenanceResponse($xml);
        $this->assertEquals(100, $maintenanceResponse->getParam('amount'));
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

        $maintenanceResponse = new DirectLinkMaintenanceResponse($xml);
        $this->assertEquals($integer, $maintenanceResponse->getParam('amount'));
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
                NCERROR="0"
                NCERRORPLUS="!"
                ACCEPTANCE=""
                STATUS="91"
                AMOUNT="'.(($amount) ? $amount : '1').'"
                CURRENCY="GBP">
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
