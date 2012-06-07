<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone;

use Ogone\ShaComposer\ShaComposer;

use InvalidArgumentException;
use RuntimeException;
use BadMethodCallException;

class PaymentRequest
{
	const TEST = "https://secure.ogone.com/ncol/test/orderstandard_utf8.asp";
	const PRODUCTION = "https://secure.ogone.com/ncol/prod/orderstandard_utf8.asp";

	private $brandsmap = array(
		'Acceptgiro' => 'Acceptgiro',
		'AIRPLUS' => 'CreditCard',
		'American Express' => 'CreditCard',
		'Aurora' => 'CreditCard',
		'Aurore' => 'CreditCard',
		'Bank transfer' => 'Bank transfer',
		'BCMC' => 'CreditCard',
		'Billy' => 'CreditCard',
		'cashU' => 'cashU',
		'CB' => 'CreditCard',
		'CBC Online' => 'CBC Online',
		'CENTEA Online' => 'CENTEA Online',
		'Cofinoga' => 'CreditCard',
		'Dankort' => 'CreditCard',
		'Dexia Direct Net' => 'Dexia Direct Net',
		'Diners Club' => 'CreditCard',
		'Direct Debits AT' => 'Direct Debits AT',
		'Direct Debits DE' => 'Direct Debits DE',
		'Direct Debits NL' => 'Direct Debits NL',
		'eDankort' => 'eDankort',
		'EPS' => 'EPS',
		'Fortis Pay Button' => 'Fortis Pay Button',
		'giropay' => 'giropay',
		'iDEAL' => 'iDEAL',
		'ING HomePay' => 'ING HomePay',
		'InterSolve' => 'InterSolve',
		'JCB' => 'CreditCard',
		'KBC Online' => 'KBC Online',
		'Maestro' => 'CreditCard',
		'MaestroUK' => 'CreditCard',
		'MasterCard' => 'CreditCard',
		'MiniTix' => 'MiniTix',
		'MPASS' => 'MPASS',
		'NetReserve' => 'CreditCard',
		'Payment on Delivery' => 'Payment on Delivery',
		'PAYPAL' => 'PAYPAL',
		'paysafecard' => 'paysafecard',
		'PingPing' => 'PingPing',
		'PostFinance + card' => 'PostFinance Card',
		'PostFinance e-finance' => 'PostFinance e-finance',
		'PRIVILEGE' => 'CreditCard',
		'Sofort Uberweisung' => 'DirectEbanking',
		'Solo' => 'CreditCard',
		'TUNZ' => 'TUNZ',
		'UATP' => 'CreditCard',
		'UNEUROCOM' => 'UNEUROCOM',
		'VISA' => 'CreditCard',
		'Wallie' => 'Wallie',
	);

	/** @var ShaComposer */
	private $shaComposer;

	private $ogoneUri = self::TEST;

	private $parameters = array();

	private $ogoneFields = array(
		'pspid', 'orderid', 'com', 'amount', 'currency', 'language', 'cn', 'email',
		'ownerzip', 'owneraddress', 'ownercty', 'ownertown', 'ownertelno', 'accepturl',
		'declineurl', 'exceptionurl', 'cancelurl', 'complus', 'paramplus', 'pm',
		'brand', 'title', 'bgcolor', 'txtcolor', 'tblbgcolor', 'tbltxtcolor', 'buttonbgcolor',
		'buttontxtcolor', 'logo', 'fonttype', 'tp', 'paramvar'
	);

	private $requiredfields = array(
		'pspid', 'currency', 'amount', 'orderid'
		// 'pm', 'brand' // left out because when missing, ogone will ask the customer for these
	);

	/** Note this is public to allow easy modification, if need be. */
	public $allowedcurrencies = array(
		'AED', 'ANG', 'ARS', 'AUD', 'AWG', 'BGN', 'BRL', 'BYR', 'CAD', 'CHF',
		'CNY', 'CZK', 'DKK', 'EEK', 'EGP', 'EUR', 'GBP', 'GEL', 'HKD', 'HRK',
		'HUF', 'ILS', 'ISK', 'JPY', 'KRW', 'LTL', 'LVL', 'MAD', 'MXN', 'NOK',
		'NZD', 'PLN', 'RON', 'RUB', 'SEK', 'SGD', 'SKK', 'THB', 'TRY', 'UAH',
		'USD', 'XAF', 'XOF', 'XPF', 'ZAR'
	);

	/** Note this is public to allow easy modification, if need be. */
	public $allowedlanguages = array(
		'en_US' => 'English', 'cs_CZ' => 'Czech', 'de_DE' => 'German',
		'dk_DK' => 'Danish', 'el_GR' => 'Greek', 'es_ES' => 'Spanish',
		'fr_FR' => 'French', 'it_IT' => 'Italian', 'ja_JP' => 'Japanese',
		'nl_BE' => 'Flemish', 'nl_NL' => 'Dutch', 'no_NO' => 'Norwegian',
		'pl_PL' => 'Polish', 'pt_PT' => 'Portugese', 'ru_RU' => 'Russian',
		'se_SE' => 'Swedish', 'sk_SK' => 'Slovak', 'tr_TR' => 'Turkish'
	);

	public function __construct(ShaComposer $shaComposer)
	{
		$this->shaComposer = $shaComposer;
	}

	/** @return string */
	public function getShaSign()
	{
		return $this->shaComposer->compose($this->toArray());
	}

	/** @return string */
	public function getOgoneUri()
	{
		return $this->ogoneUri;
	}

	/** Ogone uri to send the customer to. Usually PaymentRequest::TEST or PaymentRequest::PRODUCTION */
	public function setOgoneUri($ogoneUri)
	{
		$this->validateUri($ogoneUri);
		$this->ogoneUri = $ogoneUri;
	}

	public function setPspid($pspid)
	{
		if(strlen($pspid) > 30) {
			throw new InvalidArgumentException("PSPId is too long");
		}
		$this->parameters['pspid'] = $pspid;
	}

	public function setOrderid($orderid)
	{
		if(strlen($orderid) > 30) {
			throw new InvalidArgumentException("Orderid cannot be longer than 30 characters");
		}
		if(preg_match('/[^a-zA-Z0-9_-]/', $orderid)) {
			throw new InvalidArgumentException("Order id cannot contain special characters");
		}
		$this->parameters['orderid'] = $orderid;
	}

	/** Friend alias for setCom() */
	public function setOrderDescription($orderDescription)
	{
		$this->setCom($orderDescription);
	}

	public function setCom($com)
	{
		if(strlen($com) > 100) {
			throw new InvalidArgumentException("Order description cannot be longer than 100 characters");
		}
		$this->parameters['com'] = $com;
	}

	/**
	 * Set amount in cents, eg EUR 12.34 is written as 1234
	 */
	public function setAmount($amount)
	{
		if(!is_int($amount)) {
			throw new InvalidArgumentException("Integer expected. Amount is always in cents");
		}
		if($amount <= 0) {
			throw new InvalidArgumentException("Amount must be a positive number");
		}
		if($amount >= 1.0E+15) {
			throw new InvalidArgumentException("Amount is too high");
		}
		$this->parameters['amount'] = $amount;

	}

	public function setCurrency($currency)
	{
		if(!in_array(strtoupper($currency), $this->allowedcurrencies)) {
			throw new InvalidArgumentException("Unknown currency");
		}
		$this->parameters['currency'] = $currency;
	}

	/**
	 * ISO code eg nl-BE
	 */
	public function setLanguage($language)
	{
		if(!array_key_exists($language, $this->allowedlanguages)) {
			throw new InvalidArgumentException("Invalid language ISO code");
		}
		$this->parameters['language'] = $language;
	}

	/** Alias for setCn */
	public function setCustomername($customername)
	{
		$this->setCn($customername);
	}

	public function setCn($cn)
	{
		$this->parameters['cn'] = str_replace(array("'", '"'), '', $cn); // replace quotes
	}

	public function setEmail($email)
	{
		if(strlen($email) > 50) {
			throw new InvalidArgumentException("Email is too long");
		}
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new InvalidArgumentException("Email is invalid");
		}
		$this->parameters['email'] = $email;
	}

	public function setOwnerAddress($owneraddress)
	{
		if(strlen($owneraddress) > 35) {
			throw new InvalidArgumentException("Owner address is too long");
		}
		$this->parameters['owneraddress'] = $owneraddress;
	}

	public function setOwnerZip($ownerzip)
	{
		if(strlen($ownerzip) > 10) {
			throw new InvalidArgumentException("Owner Zip is too long");
		}
		$this->parameters['ownerzip'] = $ownerzip;
	}

	public function setOwnerTown($ownertown)
	{
		if(strlen($ownertown) > 25) {
			throw new InvalidArgumentException("Owner town is too long");
		}
		$this->parameters['ownertown'] = $ownertown;
	}

	/**
	 * Alias for setOwnercty
	 * @see http://www.iso.org/iso/country_codes/iso_3166_code_lists/english_country_names_and_code_elements.htm
	 */
	public function setOwnerCountry($ownercountry)
	{
		$this->setOwnercty($ownercountry);
	}

	/**
	 * @see http://www.iso.org/iso/country_codes/iso_3166_code_lists/english_country_names_and_code_elements.htm
	 */
	public function setOwnercty($ownercty)
	{
		if(!preg_match('/^[A-Z]{2}$/', strtoupper($ownercty))) {
			throw new InvalidArgumentException("Illegal country code");
		}
		$this->parameters['ownercty'] = strtoupper($ownercty);
	}

	/** Alias for setOwnertelno() */
	public function setOwnerPhone($ownerphone)
	{
		$this->setOwnertelno($ownerphone);
	}

	public function setOwnertelno($ownertelno)
	{
		if(strlen($ownertelno) > 30) {
			throw new InvalidArgumentException("Owner phone is too long");
		}
		$this->parameters['ownertelno'] = $ownertelno;
	}

	public function setAccepturl($accepturl)
	{
		$this->validateUri($accepturl);
		$this->parameters['accepturl'] = $accepturl;
	}

	public function setDeclineurl($declineurl)
	{
		$this->validateUri($declineurl);
		$this->parameters['declineurl'] = $declineurl;
	}

	public function setExceptionurl($exceptionurl)
	{
		$this->validateUri($exceptionurl);
		$this->parameters['exceptionurl'] = $exceptionurl;
	}

	public function setCancelurl($cancelurl)
	{
		$this->validateUri($cancelurl);
		$this->parameters['cancelurl'] = $cancelurl;
	}

	/** Alias for setComplus() */
	public function setFeedbackMessage($feedbackMessage)
	{
		$this->setComplus($feedbackMessage);
	}

	public function setComplus($complus)
	{
		$this->parameters['complus'] = $complus;
	}

	/** Alias for setParamplus */
	public function setFeedbackParams(array $feedbackParams)
	{
		$this->setParamplus($feedbackParams);
	}

	public function setParamplus(array $paramplus)
	{
		$this->parameters['paramplus'] = http_build_query($paramplus);
	}

	public function setPaymentMethod($paymentMethod)
	{
		$this->setPm($paymentMethod);
	}

	public function setPm($pm)
	{
		if(!in_array($pm, $this->brandsmap)) {
			throw new InvalidArgumentException("Unknown Payment method [$pm].");
		}
		$this->parameters['pm'] = $pm;
	}

	public function setBrand($brand)
	{
		if(!array_key_exists($brand, $this->brandsmap)) {
			throw new InvalidArgumentException("Unknown Brand [$brand].");
		}

		$this->setPaymentMethod($this->brandsmap[$brand]);
		$this->parameters['brand'] = $brand;
	}

	public function setParamvar($paramvar)
	{
		if(strlen($paramvar) < 2 || strlen($paramvar) > 50) {
			throw new InvalidArgumentException("Paramvar must be between 2 and 50 characters in length");
		}
		$this->parameters['paramvar'] = $paramvar;
	}

	/** Alias for setTp */
	public function setDynamicTemplateUri($uri)
	{
		$this->setTp($uri);
	}

	public function setTp($tp)
	{
		$this->validateUri($tp);
		$this->parameters['tp'] = $tp;
	}

	public function validate()
	{
		foreach($this->requiredfields as $field)
		{
			if(empty($this->parameters[$field])) {
				throw new RuntimeException("$field can not be empty");
			}
		}
	}

	/**
	 * Allows setting ogone parameters that don't have a setter -- usually only
	 * the unimportant ones like bgcolor, which you'd call with setBgcolor()
	 */
	public function __call($method, $args)
	{
		if(substr($method, 0, 3) == 'set') {
			$field = strtolower(substr($method, 3));
			if(in_array($field, $this->ogoneFields)) {
				$this->parameters[$field] = $args[0];
				return;
			}
		}

		if(substr($method, 0, 3) == 'get') {
			$field = strtolower(substr($method, 3));
			if(array_key_exists($field, $this->parameters)) {
				return $this->parameters[$field];
			}
		}

		throw new BadMethodCallException("Unknown method $method");
	}

	public function toArray()
	{
		$this->validate();
		return $this->parameters;
	}

	/** @return PaymentRequest */
	public static function createFromArray(ShaComposer $shaComposer, array $parameters)
	{
		$instance = new static($shaComposer);
		foreach($parameters as $key => $value)
		{
			$instance->{"set$key"}($value);
		}
		return $instance;
	}

	protected function validateUri($uri)
	{
		if(!filter_var($uri, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Uri is not valid");
		}
		if(strlen($uri) > 200) {
			throw new InvalidArgumentException("Uri is too long");
		}
	}
}
