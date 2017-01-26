<?php namespace Ogone\FlexCheckout;

use Ogone\AbstractPaymentRequest;
use Ogone\ShaComposer\ShaComposer;

class FlexCheckoutPaymentRequest extends AbstractPaymentRequest
{
	const TEST = "https://ogone.test.v-psp.com/Tokenization/HostedPage";
	const PRODUCTION = "https://secure.ogone.com/Tokenization/HostedPage";

	protected $payment_methods = [
		"CreditCard",
		"DirectDebit",
	];

	public function __construct(ShaComposer $shaComposer)
	{
		$this->shaComposer = $shaComposer;
		$this->ogoneUri    = self::TEST;
	}

	public function getCheckoutUrl()
	{
		return $this->getOgoneUri()."?". http_build_query($this->toArray());
	}

	public function getRequiredFields()
	{
		return array(
			'account.pspid',
			'alias.aliasid',
			'alias.orderid',
			'card.paymentmethod',
			'parameters.accepturl',
			'parameters.exceptionurl',
		);
	}

	public function getValidOgoneUris()
	{
		return array(self::TEST, self::PRODUCTION);
	}

	public function setPspId($pspid)
	{
		$this->parameters['account.pspid'] = $pspid;
	}

	public function setOrderId($orderid)
	{
		$this->parameters['alias.orderid'] = $orderid;
	}

	public function setAliasId(Alias $alias)
	{
		$this->parameters['alias.aliasid'] = $alias->getAlias();
	}

	public function setPm($payment_method)
	{
		if (!in_array($payment_method, $this->payment_methods)) {
			throw new \InvalidArgumentException("Unknown Payment method [$payment_method].");
		}
		$this->parameters['card.paymentmethod'] = $payment_method;
	}

	public function setAccepturl($accepturl)
	{
		$this->validateUri($accepturl);
		$this->parameters['parameters.accepturl'] = $accepturl;
	}

	public function setExceptionurl($exceptionurl)
	{
		$this->validateUri($exceptionurl);
		$this->parameters['parameters.exceptionurl'] = $exceptionurl;
	}

	public function setLanguage($language)
	{
		$this->parameters['layout.language'] = $language;
	}

	public function setShaSign()
	{
		$this->parameters['shasignature.shasign'] = parent::getShaSign();
	}

	protected function getValidOperations()
	{
		return [];
	}
}
