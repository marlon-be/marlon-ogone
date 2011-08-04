<?php
namespace Ogone;

use Ogone\ShaComposer\ShaComposer;

class ConfirmationResponse
{
	/** @var string */
	const SHASIGN_FIELD = 'shasign';

	/**
	 * Available Ogone parameters
	 * @var array
	 */
	private $ogoneParameters = array('aavaddress', 'aavcheck', 'aavzip', 'acceptance', 'alias', 'amount', 'bin', 'brand', 'cardno', 'cccty', 'cn',
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
	 * @var ShaComposer
	 */
	private $shaComposer;

	/**
	 * @param array $httpRequest Typically $_REQUEST
	 * @param ShaComposer $shaComposer
	 * @throws \InvalidArgumentException
	 */
	public function __construct(array $httpRequest, ShaComposer $shaComposer)
	{
		// set SHA composer
		$this->shaComposer = $shaComposer;

		// use lowercase internally
		$httpRequest = array_change_key_case($httpRequest, CASE_LOWER);

		// set sha sign
		$this->shaSign = $this->setShaSign($httpRequest);

		// filter request for Ogone parameters
		$this->parameters = $this->filterRequestParameters($httpRequest);
	}

	/**
	 * Filter http request parameters
	 * @param array $requestParameters
	 */
	protected function filterRequestParameters(array $httpRequest)
	{
		// filter request for Ogone parameters
		return array_intersect_key($httpRequest, array_flip($this->ogoneParameters));
	}

	/**
	 * Set Ogone SHA sign
	 * @param array $request
	 * @throws \InvalidArgumentException
	 */
	protected function setShaSign($parameters)
	{
		if(!array_key_exists(self::SHASIGN_FIELD, $parameters) || $parameters[self::SHASIGN_FIELD] == '') {
			throw new \InvalidArgumentException('SHASIGN parameter not present in parameters.');
		}
		return $parameters[self::SHASIGN_FIELD];
	}

	/**
	 * Checks if the response is valid
	 * @return bool
	 */
	public function isValid()
	{
		return $this->shaComposer->compose($this->parameters) == $this->shaSign;
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
			throw new \InvalidArgumentException('Parameter ' . $key . ' does not exist.');
		}

		return $this->parameters[$key];
	}
}