<?php
namespace Ogone;

use Ogone\ShaComposer\AbstractShaComposer;

class ConfirmationRequest
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
	private $requestParameters;
	
	/**
	 * @var string
	 */
	private $shaSign;
	
	/**
	 * @var ShaComposer
	 */
	private $shaComposer;
	
	/**
	 * @param array $request Typically $_REQUEST
	 * @param AbstractShaComposer $shaComposer
	 * @throws \InvalidArgumentException 
	 */
	public function __construct($request, AbstractShaComposer $shaComposer)
	{
		if(!is_array($request)) {
			throw new \InvalidArgumentException('Request parameter array expected.');
		}
		
		// set SHA composer
		$this->shaComposer = $shaComposer;
		
		// use lowercase internally
		$request = array_change_key_case($request, CASE_LOWER);
		
		// set sha sign
		$this->shaSign = $this->setShaSign($request);
		
		// filter request for Ogone parameters
		$this->requestParameters = $this->filterRequestParameters($request);
	}
	
	/**
	 * Filter Ogone request parameters
	 * @param array $requestParameters
	 */
	protected function filterRequestParameters($requestParameters)
	{
		// filter request for Ogone parameters
		return array_intersect_key($requestParameters, array_flip($this->ogoneParameters));
	}
	
	/**
	 * Set Ogone SHA sign
	 * @param array $request
	 * @throws \InvalidArgumentException
	 */
	protected function setShaSign($request)
	{
		if(!array_key_exists(self::SHASIGN_FIELD, $request) || $request[self::SHASIGN_FIELD] == '') {
			throw new \InvalidArgumentException('SHASIGN parameter not present in request.');
		}
		return $request[self::SHASIGN_FIELD];
	}
	
	/**
	 * Checks if the request is valid
	 * @return bool
	 */
	public function isValid()
	{
		return $this->shaComposer->compose($this->requestParameters) == $this->shaSign;
	}
	
	/**
	 * Retrieves a request parameter
	 * @param string $param
	 * @throws \InvalidArgumentException
	 */
	public function getParam($param)
	{
		// always use lowercase internally
		$param = strtolower($param);
		
		if(!array_key_exists($param, $this->requestParameters)) {
			throw new \InvalidArgumentException('Parameter ' . $param . ' does not exist.');
		}
		
		return $this->requestParameters[$param];
	}
}