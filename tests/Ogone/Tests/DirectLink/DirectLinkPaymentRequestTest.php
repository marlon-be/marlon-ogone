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

use Ogone\Tests;
use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\DirectLink\DirectLinkPaymentRequest;
use Ogone\DirectLink\Alias;

class DirectLinkPaymentRequestTest extends \TestCase {

    /** @test */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $directLinkPaymentRequest = $this->provideMinimalDirectLinkPaymentRequest();
        $directLinkPaymentRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $directLinkPaymentRequest = new DirectLinkPaymentRequest(new FakeShaComposer);
        $directLinkPaymentRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonOgoneUrl()
    {
        $directLinkPaymentRequest = $this->provideMinimalDirectLinkPaymentRequest();
        $directLinkPaymentRequest->setOgoneUri('http://example.com');
        $directLinkPaymentRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithOgoneUrl()
    {
        $directLinkPaymentRequest = $this->provideMinimalDirectLinkPaymentRequest();
        $directLinkPaymentRequest->setOgoneUri(DirectLinkPaymentRequest::PRODUCTION);
        $directLinkPaymentRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWhenAliasSet()
    {
        $alias = new Alias('customer_123');

        $directLinkPaymentRequest = $this->provideMinimalDirectLinkPaymentRequest();
        $directLinkPaymentRequest->setAlias($alias);
        $directLinkPaymentRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function IsInvalidWithTooLongAlias()
    {
        $alias = new Alias(str_repeat('repeat', 10));

        $directLinkPaymentRequest = $this->provideMinimalDirectLinkPaymentRequest();
        $directLinkPaymentRequest->setAlias($alias);
        $directLinkPaymentRequest->validate();
    }

    /**
     * @test
     * @dataProvider provideBadParameters
     * @expectedException \InvalidArgumentException
     */
    public function BadParametersCauseExceptions($method, $value)
    {
        $directLinkPaymentRequest = new DirectLinkPaymentRequest(new FakeShaComposer);
        $directLinkPaymentRequest->$method($value);
    }

    public function provideBadParameters()
    {
        return array(
            array('setPswd', '12'),
            array('setUserid', '12')
        );
    }
}
