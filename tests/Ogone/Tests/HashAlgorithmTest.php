<?php

namespace Ogone\Tests;

use Ogone\HashAlgorithm;

class HashAlgorithmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function UnknownHashAlgorithmIsInvalid()
    {
        new HashAlgorithm('md5');
    }

    /** @test */
    public function CanBeRepresentedAsString()
    {
        $sha1 = new HashAlgorithm(HashAlgorithm::HASH_SHA1);
        $this->assertEquals(HashAlgorithm::HASH_SHA1, (string) $sha1);
    }
}
