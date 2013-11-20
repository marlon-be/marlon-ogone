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

use Ogone\Alias;

class AliasTest extends \TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function AliasCannotBeNull()
    {
        new Alias(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function AliasCannotBeAnEmptyString()
    {
        new Alias('');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function AliasIsMax50Characters()
    {
        new Alias(str_repeat('X', 51));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function AliasIsAlphaNumeric()
    {
        new Alias('some alias with spaces, dots (.), etc');
    }

    /** @test */
    public function CanBeRepresentedAsString()
    {
        $this->assertEquals('test123', (string) new Alias('test123'));
    }
}
