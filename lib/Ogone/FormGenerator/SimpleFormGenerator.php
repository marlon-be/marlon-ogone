<?php
namespace Ogone\FormGenerator;

use Ogone\PaymentRequest;
use InvalidArgumentException;

class SimpleFormGenerator implements FormGenerator
{
	private $parameters;
	private $ogoneUri;

	/** @return string */
	public function render(PaymentRequest $paymentRequest)
	{
		$this->parameters = $paymentRequest->toArray();
		$this->ogoneUri = $paymentRequest->getOgoneUri();

		ob_start();
		include __DIR__.'/template/simpleForm.php';
		return ob_get_clean();
	}

	protected function getParameters()
	{
		return $this->parameters;
	}

	protected function getOgoneUri()
	{
		return $this->ogoneUri;
	}

	protected function getShaSign()
	{
		// @todo sha
	}
}
