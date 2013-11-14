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

use Ogone\EcommercePaymentRequest;

class SimpleFormGenerator implements FormGenerator
{
	private $ecommercePaymentRequest;

	private $showSubmitButton = true;

	private $formName = 'ogone';

	/** @return string */
	public function render(EcommercePaymentRequest $ecommercePaymentRequest)
	{
		$this->ecommercePaymentRequest = $ecommercePaymentRequest;
		ob_start();
		include __DIR__.'/template/simpleForm.php';
		return ob_get_clean();
	}

	protected function getParameters()
	{
		return $this->ecommercePaymentRequest->toArray();
	}

	protected function getOgoneUri()
	{
		return $this->ecommercePaymentRequest->getOgoneUri();
	}

	protected function getShaSign()
	{
		return $this->ecommercePaymentRequest->getShaSign();
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
