<?php
namespace Ogone;

use InvalidArgumentException;
use RuntimeException;

class PaymentRequest
{
	const TEST = "https://secure.ogone.com/ncol/test/orderstandard.asp";
	const PRODUCTION = "https://secure.ogone.com/ncol/prod/orderstandard.asp";

	private $actionUri = self::TEST;

	private $parameters = array();

	private $ogoneFields = array(
		'pspid', 'orderid', 'com', 'amount', 'currency', 'language', 'cn', 'email',
		'ownerzip', 'owneraddress', 'ownercty', 'ownertown', 'ownertelno', 'accepturl',
		'declineurl', 'exceptionurl', 'cancelurl', 'complus', 'paramplus', 'pm',
		'brand', 'title', 'bgcolor', 'txtcolor', 'tblbgcolor', 'tbltxtcolor', 'buttonbgcolor',
		'buttontxtcolor', 'logo', 'fonttype', 'tp', 'paramvar'
	);

	private $requiredfields = array(
		'pspid', 'cn', 'owneraddress', 'ownertown', 'ownerzip', 'ownercty', 'email',
		'pm', 'brand', 'amount', 'orderid'
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

	/** @return string */
	public function getActionUri()
	{
		return $this->actionUri;
	}

	/** Ogone uri to send the customer to. Usually PaymentRequest::TEST or PaymentRequest::PRODUCTION */
	public function setActionUri($actionUri)
	{
		if(!filter_var($actionUri, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Action uri is not valid");
		}
		$this->actionUri = $actionUri;
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
		if(strlen($amount) > 15) {
			throw new InvalidArgumentException("Amount is too high");
		}
		$this->parameters['amount'] = (int) $amount * 100;

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
		if(strlen($$ownertown) > 25) {
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
		if(!preg_match('/[A-Z]{2}$/', strtoupper($ownercty))) {
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
		if(!filter_var($accepturl, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Accepturl is not valid");
		}
		if(strlen($accepturl) > 200) {
			throw new InvalidArgumentException("Accepturl is too long");
		}
		$this->parameters['accepturl'] = $accepturl;
	}

	public function setDeclineurl($declineurl)
	{
		if(!filter_var($declineurl, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Declineurl is not valid");
		}
		if(strlen($declineurl) > 200) {
			throw new InvalidArgumentException("Declineurl is too long");
		}
		$this->parameters['declineurl'] = $declineurl;
	}

	public function setExceptionurl($exceptionurl)
	{
		if(!filter_var($exceptionurl, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Exceptionurl is not valid");
		}
		if(strlen($exceptionurl) > 200) {
			throw new InvalidArgumentException("Exceptionurl is too long");
		}
		$this->parameters['exceptionurl'] = $exceptionurl;
	}

	public function setCancelurl($cancelurl)
	{
		if(!filter_var($cancelurl, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Cancelurl is not valid");
		}
		if(strlen($cancelurl) > 200) {
			throw new InvalidArgumentException("Cancelurl is too long");
		}
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
		$this->parameters['pm'] = $pm;
	}

	public function setBrand($brand)
	{
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
		if(!filter_var($tp, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("TP (Dynamic template uri) is not valid");
		}
		$this->parameters['tp'] = $tp;
	}

	public function validate()
	{
		// @todo validate sha here?

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
			}
		}

		throw new BadMethodCallException("Unknown method $method");
	}

	public function toArray()
	{
		$this->validate();
		return $this->parameters;
	}
}
