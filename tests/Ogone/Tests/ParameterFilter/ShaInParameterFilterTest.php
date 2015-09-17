<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests\ShaComposer;

use Ogone\ParameterFilter\ShaInParameterFilter;

class ShaInParameterFilterTest extends \TestCase
{
    /** @test */
    public function RemovesUnwantedParameters()
    {
        $filter = new ShaInParameterFilter;
        $result = $filter->filter(array('foo' => 'bar', 'orderId' => 123));
        $this->assertEquals(array('ORDERID' => 123), $result);
    }
}
