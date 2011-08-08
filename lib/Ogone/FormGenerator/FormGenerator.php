<?php
namespace Ogone\FormGenerator;

use Ogone\PaymentRequest;

interface FormGenerator
{
	/** @return string Html */
	function render(PaymentRequest $paymentRequest);
}