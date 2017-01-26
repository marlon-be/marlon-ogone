<?php namespace Ogone\Tests\FlexCheckout;

use GuzzleHttp\Client;
use Ogone\FlexCheckout\FlexCheckoutPaymentRequest;
use Ogone\FlexCheckout\Alias;

use Ogone\FlexCheckout\FlexCheckoutPaymentResponse;
use Ogone\HashAlgorithm;
use Ogone\Passphrase;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\ShaComposer\ShaComposer;
use TestCase;

class FlexCheckoutPaymentResponseTest extends TestCase
{
	private $configuration;

	public function testResponse()
	{
		$parameters = [
			"Alias_AliasId"       => "4",
			"Card_Bin"            => "411111",
			"Card_Brand"          => "VISA",
			"Card_CardNumber"     => "XXXXXXXXXXXX1111",
			"Card_CardHolderName" => "Holder Test",
			"Card_Cvc"            => "XXX",
			"Card_ExpiryDate"     => "0319",
			"Alias_NCError"       => "0",
			"Alias_NCErrorCardNo" => "0",
			"Alias_NCErrorCN"     => "0",
			"Alias_NCErrorCVC"    => "0",
			"Alias_NCErrorED"     => "0",
			"Alias_OrderId"       => "4422",
			"Alias_Status"        => "2",
			"SHASign"             => "BD9F63BC7FA689E690957DDA1C6E8BDD4A5F1A5F",
		];

		$sha  = new FakeShaComposer;
		$flex = new FlexCheckoutPaymentResponse($parameters);
		$flex->isValid($sha);
	}

	/**
	 * @return AllParametersShaComposer
	 */
	protected function getShaComposer()
	{
		$ogone_config = config("ogone");

		$this->configuration = [
			'sha_in'          => $ogone_config["sha_in"],
			'sha_out'         => $ogone_config["sha_out"],
			'psp_id'          => $ogone_config["psp_id"],
			'user_id'         => $ogone_config["user_id"],
			'password'        => $ogone_config["password"],
			'url_payment'     => $ogone_config["url_payment"],
			'url_maintenance' => $ogone_config["url_maintenance"],
		];

		$passphrase    = new Passphrase($this->configuration['sha_out']);
		$hash_algoritm = new HashAlgorithm(HashAlgorithm::HASH_SHA1);
		$shaComposer   = new AllParametersShaComposer($passphrase, $hash_algoritm);
		return $shaComposer;
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

