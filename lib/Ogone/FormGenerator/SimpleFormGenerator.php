<?php
namespace Ogone\FormGenerator;

use Ogone\PaymentRequest;
use InvalidArgumentException;

class SimpleFormGenerator implements FormGenerator
{
	private $paymentRequest;

	/** @return string */
	public function render(PaymentRequest $paymentRequest)
	{
		$this->paymentRequest = $paymentRequest;
		ob_start();
		include __DIR__.'/template/simpleForm.php';
		return ob_get_clean();
	}

	protected function getParameters()
	{
		return $this->paymentRequest->toArray();
	}

	protected function getOgoneUri()
	{
		return $this->paymentRequest->getOgoneUri();
	}

	protected function getShaSign()
	{
		return $this->paymentRequest->getShaSign();
	}
}
