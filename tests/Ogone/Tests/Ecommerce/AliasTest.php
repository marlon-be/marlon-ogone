<?php
namespace Ogone\Tests\Ecommerce;

use Ogone\Ecommerce\Alias;
use TestCase;

class AliasTest extends TestCase
{

    /** @test */
    public function AliasCanHaveUsage()
    {
        $alias = new Alias('alias123', null, 'usage...');
        $this->assertEquals('usage...', $alias->getAliasUsage());
    }

    /** @test */
    public function AliasCanHaveOperationByMerchant()
    {
        $alias = new Alias('alias123');
        $this->assertEquals(Alias::OPERATION_BY_MERCHANT, $alias->getAliasOperation());
    }

    /** @test */
    public function AliasCanHaveOperationByPsp()
    {
        $alias = new Alias('alias123', Alias::OPERATION_BY_PSP);
        $this->assertEquals(Alias::OPERATION_BY_PSP, $alias->getAliasOperation());
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
