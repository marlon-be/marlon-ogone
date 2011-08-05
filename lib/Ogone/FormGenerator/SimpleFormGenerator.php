<?php
namespace Ogone\FormGenerator;
use InvalidArgumentException;

class SimpleFormGenerator implements FormGenerator
{
	private $parameters;
	private $ogoneUri;

	/** @return string */
	public function render(PaymentRequest $paymentRequest)
	{
		$this->parameters = $paymentRequest->toArray();
		$this->ogoneUri = $paymentRequest->getOgonenUri();

		ob_start();
		include __DIR__.'/Template/form.php';
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
}
