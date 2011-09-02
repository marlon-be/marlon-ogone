<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\FormGenerator;

use Ogone\PaymentRequest;
use InvalidArgumentException;

class SimpleFormGenerator implements FormGenerator
{
	private $paymentRequest;

	private $showSubmitButton = true;

	private $formName = 'ogone';

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

	public function showSubmitButton($bool = true)
	{
		$this->showSubmitButton = $bool;
	}

	public function setFormName($formName)
	{
		$this->formName = $formName;
	}
}
