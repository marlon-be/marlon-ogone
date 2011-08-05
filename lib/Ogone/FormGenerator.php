<?php
namespace Ogone;
use InvalidArgumentException;

class FormGenerator
{
	private $parameters;
	private $actionUri;

	/** @return string */
	public function render(PaymentRequest $paymentRequest)
	{
		$this->parameters = $paymentRequest->toArray();
		$this->actionUri = $paymentRequest->getActionUri();

		ob_start();
		include __DIR__.'/Template/form.php';
		return ob_get_clean();
	}

	protected function getParameters()
	{
		return $this->parameters;
	}

	protected function getActionUri()
	{
		return $this->actionUri;
	}
}
