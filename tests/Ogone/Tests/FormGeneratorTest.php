<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests;

use Ogone\PaymentRequest;
use Ogone\FormGenerator\SimpleFormGenerator;

class SimpleFormGeneratorTest extends \TestCase
{
	/** @test */
	public function GeneratesAForm()
	{
		$expected =
			'<form method="post" action="https://secure.ogone.com/ncol/test/orderstandard_utf8.asp" id="ogone" name="ogone">
				<input type="hidden" name="pspid" value="123456789" />
				<input type="hidden" name="orderid" value="987654321" />
				<input type="hidden" name="currency" value="EUR" />
				<input type="hidden" name="amount" value="100" />
				<input type="hidden" name="cn" value="Louis XIV" />
				<input type="hidden" name="owneraddress" value="1, Rue du Palais" />
				<input type="hidden" name="ownertown" value="Versailles" />
				<input type="hidden" name="ownerzip" value="2300" />
				<input type="hidden" name="ownercty" value="FR" />
				<input type="hidden" name="email" value="louis.xiv@versailles.fr" />

				<input type="hidden" name="SHASIGN" value="foo" />
				<input type="submit" value="Submit" id="ogonesubmit" name="ogonesubmit" />
			</form>';

		$paymentRequest = $this->provideMinimalPaymentRequest();
		$formGenerator = new SimpleFormGenerator;

		$html = $formGenerator->render($paymentRequest);

		$this->assertXmlStringEqualsXmlString($expected, $html);
	}
}