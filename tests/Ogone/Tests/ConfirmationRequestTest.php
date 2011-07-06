<?php
namespace Ogone\Tests;

use Ogone\ShaComposer\ShaComposer;
use Ogone\ShaComposer\MainParametersShaComposer;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\ConfirmationRequest;

class ConfirmationRequestTest extends \TestCase
{
	const SHASTRING = 'foo';
	
	/** @test */
	public function CanBeVerified()
	{	
		$confirmationRequest = new ConfirmationRequest($this->setupRequest(), new FakeShaComposer());
		$this->assertTrue($confirmationRequest->isValid());
	}
	
	/** 
	 * @test
	 * @expectedException InvalidArgumentException 
	*/
	public function CannotExistWithoutShaSign()
	{
		$confirmationRequest = new ConfirmationRequest(array(), new FakeShaComposer());
	}
	
	/** @test */
	public function ParametersCanBeRetrieved()
	{
		$request = $this->setupRequest();
		
		$confirmationRequest = new ConfirmationRequest($request, new FakeShaComposer());
		$this->assertEquals($request['orderID'], $confirmationRequest->getParam('orderid'));
	}
	
	/** 
	* @test
	* @expectedException InvalidArgumentException 
	*/
	public function RequestIsFilteredFromNonOgoneParameters()
	{
		$confirmationRequest = new ConfirmationRequest($this->setupRequest(), new FakeShaComposer());
		$confirmationRequest->getParam('unknown_param');
	}
	
	/** 
	 * @test
	 * @expectedException InvalidArgumentException
	*/
	public function OnlyAcceptsARequestArray()
	{
		$confirmationRequest = new ConfirmationRequest(new \stdClass(), new FakeShaComposer());
	}
	
	/**
	 * Helper method to setup a request array
	 */
	private function setupRequest()
	{
		return array(
			'orderID' => '123',
			'SHASIGN' => self::SHASTRING,
			'UNKNOWN_PARAM' => false /* unkown parameter, should be filtered out */
		);
	}
}

/**
 * Fake SHA Composer to decouple test from actual SHA composers
 */
class FakeShaComposer implements ShaComposer
{
	public function compose($requestParameters)
	{
		return ConfirmationRequestTest::SHASTRING;
	}
}