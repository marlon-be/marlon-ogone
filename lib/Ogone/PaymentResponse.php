<?php
namespace Ogone;

use Ogone\ShaComposer\ShaComposer;
use InvalidArgumentException;

class PaymentResponse
{
	/** @var string */
	const SHASIGN_FIELD = 'shasign';

	/**
	 * Available Ogone parameters
	 * @var array
	 */
	private $ogoneFields = array('aavaddress', 'aavcheck', 'aavzip', 'acceptance', 'alias', 'amount', 'bin', 'brand', 'cardno', 'cccty', 'cn',
		'complus', 'creation_status', 'currency', 'cvccheck', 'dcc_commpercentage', 'dcc_convamount', 'dcc_convccy', 'dcc_exchrate', 'dcc_exchratesource',
		'dcc_exchratets', 'dcc_indicator', 'dcc_marginpercentage', 'dcc_validhours', 'digestcardno', 'eci', 'ed', 'enccardno', 'ip', 'ipcty',
		'nbremailusage','nbripusage', 'nbripusage_alltx', 'nbrusage', 'ncerror', 'orderid', 'payid', 'pm', 'sco_category', 'scoring', 'status',
		'subscription_id', 'trxdate','vc');

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
		$httpRequest = array_change_key_case($httpRequest, CASE_LOWER);

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
		// always use lowercase internally
		$key = strtolower($key);

		if(!array_key_exists($key, $this->parameters)) {
			throw new InvalidArgumentException('Parameter ' . $key . ' does not exist.');
		}

		return $this->parameters[$key];
	}

	public function isSuccessful()
	{
		// @todo use constants
		return in_array($this->getParam('status'), array(5, 9));
	}
}