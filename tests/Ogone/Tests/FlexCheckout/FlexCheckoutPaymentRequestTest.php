<?php namespace Ogone\Tests\FlexCheckout;

use GuzzleHttp\Client;
use Ogone\FlexCheckout\FlexCheckoutPaymentRequest;
use Ogone\FlexCheckout\Alias;

use Ogone\HashAlgorithm;
use Ogone\Passphrase;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\ShaComposer\ShaComposer;
use TestCase;

class FlexCheckoutPaymentRequestTest extends TestCase
{
	private $configuration;

	public function testCheckoutUrl()
	{
		$sha = new FakeShaComposer;

		$flex        = new FlexCheckoutPaymentRequest($sha);
		$alias       = new Alias("test111");
		$uri = "http://www.example.com";

		$flex->setPspId($this->configuration['psp_id']);
		$flex->setAliasId($alias);
		$flex->setOrderId("test20111");
		$flex->setPaymentMethod("CreditCard");
		$flex->setAccepturl($uri);
		$flex->setExceptionurl($uri);
		$flex->setShaSign();
		$flex->validate();

		$url = $flex->getCheckoutUrl();

		$this->assertEquals($url,
			"https://ogone.test.v-psp.com/Tokenization/HostedPage?ACCOUNT.PSPID=0d8ufu089ad&ALIAS.ALIASID=test111&ALIAS.ORDERID=test20111&CARD.PAYMENTMETHOD=CreditCard&PARAMETERS.ACCEPTURL=http%3A%2F%2Fwww.example.com&PARAMETERS.EXCEPTIONURL=http%3A%2F%2Fwww.example.com&SHASIGNATURE.SHASIGN=foo");
	}
}

class FakeShaComposer implements ShaComposer
{
    const FAKESHASTRING = 'foo';

    public function compose(array $responseParameters)
    {
        return self::FAKESHASTRING;
    }
}

