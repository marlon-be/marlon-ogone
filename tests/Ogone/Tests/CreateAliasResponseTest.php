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

use Ogone\CreateAliasResponse;
use Ogone\Tests\ShaComposer\FakeShaComposer;

class CreateAliasResponseTest extends \TestCase {

    /** @test */
    public function CanBeVerified()
    {
        $aRequest = $this->provideRequest();

        $createAliasResponse = new CreateAliasResponse($aRequest);
        $this->assertTrue($createAliasResponse->isValid(new FakeShaComposer));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function CannotExistWithoutShaSign()
    {
        $createAliasResponse = new CreateAliasResponse(array());
    }

    /** @test */
    public function ParametersCanBeRetrieved()
    {
        $aRequest = $this->provideRequest();

        $createAliasResponse = new CreateAliasResponse($aRequest);
        $this->assertEquals($aRequest['orderID'], $createAliasResponse->getParam('orderid'));
    }

    /** @test */
    public function ChecksStatus()
    {
        $aRequest = $this->provideRequest();

        $createAliasResponse = new CreateAliasResponse($aRequest);
        $this->assertTrue($createAliasResponse->isSuccessful());
    }

    /** @test */
    public function AliasIsEqual()
    {
        $aRequest = $this->provideRequest();
        $createAliasResponse = new CreateAliasResponse($aRequest);
        $alias = $createAliasResponse->getAlias();
        $this->assertEquals('customer_123', $alias->__toString());
    }

    /**
     * Helper method to setup a request array
     */
    private function provideRequest()
    {
        return array(
            'SHASIGN' => FakeShaComposer::FAKESHASTRING,
            'UNKNOWN_PARAM' => false, /* unkown parameter, should be filtered out */
            'status' => 0,
            'orderID' => '48495482424',
            'alias' => 'customer_123',
            'cardno' => 'xxx-xxxxxxxxxx-xx'
        );
    }

} 