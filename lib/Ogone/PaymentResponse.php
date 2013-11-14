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

class PaymentResponse
{
	/** @var string */
	const SHASIGN_FIELD = 'SHASIGN';

	/** Response statusses */
	const PAYMENT_INVALID = 0;	
	const PAYMENT_CANCELLED = 1;
	const PAYMENT_AUTHORISATION_REFUSED = 2;
	const ORDER_STORED = 4;
	const PAYMENT_AUTHORISED = 5;
	const PAYMENT_AUTHORISED_AND_CANCELLED = 6;
	const PAYMENT_DELETED = 7;
	const PAYMENT_REFUND = 8;
	const PAYMENT_REQUESTED = 9;


	/**
	 * Available Ogone parameters
	 * @var array
	 */
	private $ogoneFields = array('AAVADDRESS', 'AAVCHECK', 'AAVZIP', 'ACCEPTANCE', 'ALIAS', 'AMOUNT', 'BIN', 'BRAND', 'CARDNO', 'CCCTY', 'CN',
		'COMPLUS', 'CREATION_STATUS', 'CURRENCY', 'CVCCHECK', 'DCC_COMMPERCENTAGE', 'DCC_CONVAMOUNT', 'DCC_CONVCCY', 'DCC_EXCHRATE', 'DCC_EXCHRATESOURCE',
		'DCC_EXCHRATETS', 'DCC_INDICATOR', 'DCC_MARGINPERCENTAGE', 'DCC_VALIDHOURS', 'DIGESTCARDNO', 'ECI', 'ED', 'ENCCARDNO', 'IP', 'IPCTY',
		'NBREMAILUSAGE','NBRIPUSAGE', 'NBRIPUSAGE_ALLTX', 'NBRUSAGE', 'NCERROR', 'ORDERID', 'PAYID', 'PM', 'SCO_CATEGORY', 'SCORING', 'STATUS',
		'SUBSCRIPTION_ID', 'TRXDATE','VC');

	/**
	 * @var array
	 */
	private $parameters;

	/**
	 * @var string
	 */
	private $shaSign;

	/**
	 * @param array $httpRequest Typically $_REQUEST
	 * @throws \InvalidArgumentException
	 */
	public function __construct(array $httpRequest)
	{
		// use lowercase internally
		$httpRequest = array_change_key_case($httpRequest, CASE_UPPER);

		// set sha sign
		$this->shaSign = $this->extractShaSign($httpRequest);

		// filter request for Ogone parameters
		$this->parameters = $this->filterRequestParameters($httpRequest);
	}

	/**
	 * Filter http request parameters
	 * @param array $requestParameters
	 */
	private function filterRequestParameters(array $httpRequest)
	{
		// filter request for Ogone parameters
		return array_intersect_key($httpRequest, array_flip($this->ogoneFields));
	}

	/**
	 * Set Ogone SHA sign
	 * @param array $parameters
	 * @throws \InvalidArgumentException
	 */
	private function extractShaSign(array $parameters)
	{
		if(!array_key_exists(self::SHASIGN_FIELD, $parameters) || $parameters[self::SHASIGN_FIELD] == '') {
			throw new InvalidArgumentException('SHASIGN parameter not present in parameters.');
		}
		return $parameters[self::SHASIGN_FIELD];
	}

	/**
	 * Checks if the response is valid
	 * @return bool
	 */
	public function isValid(ShaComposer $shaComposer)
	{		
		return $shaComposer->compose($this->parameters) == $this->shaSign;
	}

	/**
	 * Retrieves a response parameter
	 * @param string $param
	 * @throws \InvalidArgumentException
	 */
	public function getParam($key)
	{
		if(method_exists($this, 'get'.$key)) {
			return $this->{'get'.$key}();
		}

		// always use uppercase
		$key = strtoupper($key);

		if(!array_key_exists($key, $this->parameters)) {
			throw new InvalidArgumentException('Parameter ' . $key . ' does not exist.');
		}

		return $this->parameters[$key];
	}

	/**
	 * @return int Amount in cents
	 */
	public function getAmount()
	{
		$value = trim($this->parameters['AMOUNT']);

		$withoutDecimals = '#^\d*$#';
		$oneDecimal = '#^\d*\.\d$#';
		$twoDecimals = '#^\d*\.\d\d$#';

		if(preg_match($withoutDecimals, $value)) {
			return (int) ($value.'00');
		}

		if(preg_match($oneDecimal, $value)) {
			return (int) (str_replace('.', '', $value).'0');
		}

		if(preg_match($twoDecimals, $value)) {
			return (int) (str_replace('.', '', $value));
		}

		throw new \InvalidArgumentException("Not a valid currency amount");
	}

	public function isSuccessful()
	{
		return in_array($this->getParam('STATUS'), array(self::PAYMENT_AUTHORISED, self::PAYMENT_REQUESTED));
	}

	public function toArray()
	{
		return $this->parameters + array('SHASIGN' => $this->shaSign);
	}
}