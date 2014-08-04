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

use Ogone\Ecommerce\EcommercePaymentRequest;

class SimpleFormGenerator implements FormGenerator
{
	/** @return string */
	public static function render(EcommercePaymentRequest $ecommercePaymentRequest, $formName = 'ogone', $showSubmitButton = true)
	{
		ob_start();
		include __DIR__.'/template/simpleForm.php';
		return ob_get_clean();
	}
}
