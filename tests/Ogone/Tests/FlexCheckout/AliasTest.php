<?php namespace Ogone\Tests\FlexCheckout;

use Ogone\FlexCheckout\Alias;
use TestCase;

class AliasTest extends TestCase
{

    /** @test */
    public function AliasCanHaveUsage()
    {
        $alias = new Alias('alias123');
        $this->assertEquals('alias123', $alias->getAlias());
    }
}
