<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\Tests\DirectLink;

use Ogone\Tests;
use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\DirectLink\DirectLinkMaintenanceRequest;
use Ogone\DirectLink\Alias;

class DirectLinkMaintenanceRequestTest extends \TestCase {

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
            array('setUserid', '12'),
            array('setOperation', 'ABC'),
        );
    }
}
