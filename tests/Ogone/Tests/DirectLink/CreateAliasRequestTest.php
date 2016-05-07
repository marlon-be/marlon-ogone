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

use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\DirectLink\CreateAliasRequest;
use Ogone\DirectLink\Alias;

class CreateAliasRequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function IsValidWhenRequiredFieldsAreFilledIn()
    {
        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function isInvalidWithNonOgoneUrl()
    {
        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setOgoneUri('http://example.com');
        $aliasRequest->validate();
    }

    /**
     * @test
     */
    public function isValidWithOgoneUrl()
    {
        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setOgoneUri(CreateAliasRequest::PRODUCTION);
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function IsInvalidWhenFieldsAreMissing()
    {
        $aliasRequest = new CreateAliasRequest(new FakeShaComposer);
        $aliasRequest->validate();
    }

    /**
     * @test
     */
    public function IsValidWithAliasSet()
    {
        $alias = new Alias('customer_123');

        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setAlias($alias);
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function IsInvalidWithTooLongAlias()
    {
        $alias = new Alias(str_repeat('repeat', 10));

        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setAlias($alias);
        $aliasRequest->validate();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function IsInvalidWithAliasWrongCharacter()
    {
        $alias = new Alias('customer_#!§?');

        $aliasRequest = $this->provideMinimalAliasRequest();
        $aliasRequest->setAlias($alias);
        $aliasRequest->validate();
    }

    /** @return CreateAliasRequest*/
    private function provideMinimalAliasRequest()
    {
        $aliasRequest = new CreateAliasRequest(new FakeShaComposer);
        $aliasRequest->setPspid('18457454');
        $aliasRequest->setAccepturl('http://example.com/accept');
        $aliasRequest->setExceptionurl('http://example.com/exception');

        return $aliasRequest;
    }
}
