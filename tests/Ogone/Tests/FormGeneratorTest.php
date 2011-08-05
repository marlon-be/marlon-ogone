<?php
namespace Ogone\Tests;

use Ogone\PaymentRequest;
use Ogone\FormGenerator\SimpleFormGenerator;

class SimpleFormGeneratorTest extends \TestCase
{
	/** @test */
	public function GeneratesAForm()
	{
		$paymentRequest = $this->provideMinimalPaymentRequest();
		$formGenerator = new SimpleFormGenerator;

		$html = $formGenerator->render($paymentRequest);

		//echo $html;
	}
}