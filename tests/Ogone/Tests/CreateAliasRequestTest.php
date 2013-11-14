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

use Ogone\Tests\ShaComposer\FakeShaComposer;
use Ogone\CreateAliasRequest;

class CreateAliasRequestTest extends \TestCase {

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
} 