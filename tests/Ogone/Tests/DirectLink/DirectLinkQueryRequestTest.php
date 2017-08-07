<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\Tests\DirectLink;

use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\DirectLink\DirectLinkQueryRequest;

class DirectLinkQueryRequestTest extends \PHPUnit_Framework_TestCase
{

    /** @test */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $directLinkQueryRequest = $this->provideMinimalDirectLinkQueryRequest();
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $directLinkQueryRequest = new DirectLinkQueryRequest(new FakeShaComposer);
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonOgoneUrl()
    {
        $directLinkQueryRequest = $this->provideMinimalDirectLinkQueryRequest();
        $directLinkQueryRequest->setOgoneUri('http://example.com');
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithOgoneUrl()
    {
        $directLinkQueryRequest = $this->provideMinimalDirectLinkQueryRequest();
        $directLinkQueryRequest->setOgoneUri(DirectLinkQueryRequest::PRODUCTION);
        $directLinkQueryRequest->validate();
    }

    /**
     * @test
     * @dataProvider provideBadParameters
     * @expectedException \InvalidArgumentException
     */
    public function BadParametersCauseExceptions($method, $value)
    {
        $directLinkQueryRequest = new DirectLinkQueryRequest(new FakeShaComposer);
        $directLinkQueryRequest->$method($value);
    }

    public function provideBadParameters()
    {
        return array(
            array('setPassword', '12'),
            array('setUserid', '1'),
        );
    }

    /** @return DirectLinkQueryRequest */
    private function provideMinimalDirectLinkQueryRequest()
    {
        $directLinkRequest = new DirectLinkQueryRequest(new FakeShaComposer());
        $directLinkRequest->setPspid('123456');
        $directLinkRequest->setUserId('user_1234');
        $directLinkRequest->setPassword('abracadabra');
        $directLinkRequest->setPayId('12345678');

        return $directLinkRequest;
    }
}
