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
		$aRequest = $this->provideRequest();

		$confirmationResponse = new ConfirmationResponse($aRequest, new FakeShaComposer());
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
		$aRequest = $this->provideRequest();

		$confirmationResponse = new ConfirmationResponse($aRequest, new FakeShaComposer());
		$this->assertEquals($aRequest['orderID'], $confirmationResponse->getParam('orderid'));
	}

	/**
	* @test
	* @expectedException InvalidArgumentException
	*/
	public function RequestIsFilteredFromNonOgoneParameters()
	{
		$aRequest = $this->provideRequest();

		$confirmationResponse = new ConfirmationResponse($aRequest, new FakeShaComposer());
		$confirmationResponse->getParam('unknown_param');
	}

	/**
	 * Helper method to setup a request array
	 */
	private function provideRequest()
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