<?php
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