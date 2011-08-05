<?php
namespace Ogone\Tests;

use Ogone\PaymentRequest;

class PaymentRequestTest extends \TestCase
{
	/** @test */
	public function IsValidWhenRequiredFieldsAreFilledIn()
	{
		$paymentRequest = $this->provideMinimalPaymentRequest();
		$paymentRequest->validate();
	}

	/** @test */
	public function IsValidWhenAllFieldsAreFilledIn()
	{
		$paymentRequest = $this->provideCompletePaymentRequest();
		$paymentRequest->validate();
	}

	/**
	 * @test
	 * @expectedException \RuntimeException
	 */
	public function IsInvalidWhenFieldsAreMissing()
	{
		$paymentRequest = new PaymentRequest;
		$paymentRequest->validate();
	}

	/** @test */
	public function UnimportantParamsUseMagicSetters()
	{
		$paymentRequest = new PaymentRequest;
		$paymentRequest->setBgcolor('FFFFFF');
		$this->assertEquals('FFFFFF', $paymentRequest->getBgcolor());
	}

	/**
	 * @test
	 * @dataProvider provideBadParameters
	 * @expectedException \InvalidArgumentException
	 */
	public function BadParametersCauseExceptions($method, $value)
	{
		$paymentRequest = new PaymentRequest;
		$paymentRequest->$method($value);
	}

	/**
	 * @test
	 * @expectedException \BadMethodCallException
	 */
	public function UnknownMethodFails()
	{
		$paymentRequest = new PaymentRequest;
		$paymentRequest->getFoobar();
	}


	public function provideBadParameters()
	{
		$longString = str_repeat('longstring', 100);
		$notAUri = 'http://not a uri';
		$longUri = "http://www.example.com/$longString";

		return array(
			array('setAccepturl', $notAUri),
			array('setAmount', 10.50),
			array('setAmount', -1),
			array('setAmount', 12345678901234567890),
			//array('setBrand', ''),
			array('setCancelurl', $notAUri),
			array('setCurrency', 'Belgische Frank'),
			//array('setCustomername', ''),
			array('setDeclineurl', $notAUri),
			array('setDynamicTemplateUri', $notAUri),
			array('setEmail', 'foo @ bar'),
			array('setEmail', "$longstring@example.com"),
			array('setExceptionurl', $notAUri),
			//array('setFeedbackMessage', ''),
			//array('setFeedbackParams', ''),
			array('setLanguage', 'West-Vlaams'),
			array('setOgoneUri', $notAUri),
			array('setOrderDescription', $longString),
			array('setOrderid', "Weird çh@®a©†€rs"),
			array('setOrderid', $longString),
			array('setOwnerAddress', $longString),
			array('setOwnercountry', 'Benidorm'),
			array('setOwnerPhone', $longString),
			array('setOwnerTown', $longString),
			array('setOwnerZip', $longString),
			array('setParamvar', $longString),
			//array('setPaymentMethod', ''),
			array('setPspid', $longString),
		);
	}
}