<?php
namespace Ogone\FormGenerator;

interface FormGenerator
{
	/** @return string Html */
	function render(PaymentRequest $paymentRequest);
}