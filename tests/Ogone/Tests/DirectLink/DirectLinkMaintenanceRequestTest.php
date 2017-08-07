<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\Tests\DirectLink;

use Ogone\DirectLink\MaintenanceOperation;
use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\DirectLink\DirectLinkMaintenanceRequest;

class DirectLinkMaintenanceRequestTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $directLinkMaintenanceRequest = new DirectLinkMaintenanceRequest(new FakeShaComposer);
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonOgoneUrl()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->setOgoneUri('http://example.com');
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithOgoneUrl()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->setOgoneUri(DirectLinkMaintenanceRequest::PRODUCTION);
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithIntegerAmount()
    {
        $directLinkMaintenanceRequest = $this->provideMinimalDirectLinkMaintenanceRequest();
        $directLinkMaintenanceRequest->setAmount(232);
        $directLinkMaintenanceRequest->validate();
    }

    /**
     * @test
     * @dataProvider provideBadParameters
     * @expectedException \InvalidArgumentException
     */
    public function BadParametersCauseExceptions($method, $value)
    {
        $directLinkMaintenanceRequest = new DirectLinkMaintenanceRequest(new FakeShaComposer);
        $directLinkMaintenanceRequest->$method($value);
    }

    public function provideBadParameters()
    {
        return array(
            array('setPassword', '12'),
            array('setUserid', '1'),
            array('setAmount', '232'),
            array('setAmount', 2.32),
        );
    }

    /** @return DirectLinkMaintenanceRequest */
    private function provideMinimalDirectLinkMaintenanceRequest()
    {
        $directLinkRequest = new DirectLinkMaintenanceRequest(new FakeShaComposer());
        $directLinkRequest->setPspid('123456');
        $directLinkRequest->setUserId('user_1234');
        $directLinkRequest->setPassword('abracadabra');
        $directLinkRequest->setPayId('12345678');
        $directLinkRequest->setOperation(new MaintenanceOperation(MaintenanceOperation::OPERATION_AUTHORISATION_RENEW));

        return $directLinkRequest;
    }
}
