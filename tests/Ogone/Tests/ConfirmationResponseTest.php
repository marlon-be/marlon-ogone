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

		$confirmationResponse = new ConfirmationResponse($aRequest);
		$this->assertTrue($confirmationResponse->isValid(new FakeShaComposer()));
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	*/
	public function CannotExistWithoutShaSign()
	{
		$confirmationResponse = new ConfirmationResponse(array());
	}

	/** @test */
	public function ParametersCanBeRetrieved()
	{
		$aRequest = $this->provideRequest();

		$confirmationResponse = new ConfirmationResponse($aRequest);
		$this->assertEquals($aRequest['orderID'], $confirmationResponse->getParam('orderid'));
	}

	/**
	* @test
	* @expectedException InvalidArgumentException
	*/
	public function RequestIsFilteredFromNonOgoneParameters()
	{
		$aRequest = $this->provideRequest();

		$confirmationResponse = new ConfirmationResponse($aRequest);
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