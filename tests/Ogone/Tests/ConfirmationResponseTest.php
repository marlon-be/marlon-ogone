<?php
namespace Ogone\Tests;

use Ogone\ShaComposer\ShaComposer;
use Ogone\ShaComposer\MainParametersShaComposer;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\ConfirmationResponse;

class ConfirmationResponseTest extends \TestCase
{
	const SHASTRING = 'foo';

	/** @test */
	public function CanBeVerified()
	{
		$confirmationResponse = new ConfirmationResponse($this->setupRequest(), new FakeShaComposer());
		$this->assertTrue($confirmationResponse->isValid());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	*/
	public function CannotExistWithoutShaSign()
	{
		$confirmationResponse = new ConfirmationResponse(array(), new FakeShaComposer());
	}

	/** @test */
	public function ParametersCanBeRetrieved()
	{
		$request = $this->setupRequest();

		$confirmationResponse = new ConfirmationResponse($request, new FakeShaComposer());
		$this->assertEquals($request['orderID'], $confirmationResponse->getParam('orderid'));
	}

	/**
	* @test
	* @expectedException InvalidArgumentException
	*/
	public function RequestIsFilteredFromNonOgoneParameters()
	{
		$confirmationResponse = new ConfirmationResponse($this->setupRequest(), new FakeShaComposer());
		$confirmationResponse->getParam('unknown_param');
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
	public function compose(array $responseParameters)
	{
		return ConfirmationResponseTest::SHASTRING;
	}
}