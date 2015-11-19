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

use InvalidArgumentException;

abstract class AbstractResponse implements Response
{

    /**
     * Available Ogone parameters
     * @var array
     */
    protected $ogoneFields = array('AAVADDRESS', 'AAVCHECK', 'AAVZIP', 'ACCEPTANCE', 'ALIAS', 'AMOUNT', 'BIN', 'BRAND', 'CARDNO', 'CCCTY', 'CN',
        'COMPLUS', 'CREATION_STATUS', 'CURRENCY', 'CVC', 'CVCCHECK', 'DCC_COMMPERCENTAGE', 'DCC_CONVAMOUNT', 'DCC_CONVCCY', 'DCC_EXCHRATE', 'DCC_EXCHRATESOURCE',
        'DCC_EXCHRATETS', 'DCC_INDICATOR', 'DCC_MARGINPERCENTAGE', 'DCC_VALIDHOURS', 'DIGESTCARDNO', 'ECI', 'ED', 'ENCCARDNO', 'IP', 'IPCTY',
        'NBREMAILUSAGE','NBRIPUSAGE', 'NBRIPUSAGE_ALLTX', 'NBRUSAGE', 'NCERROR', 'NCERRORPLUS', 'NCERRORCN', 'NCERRORCARDNO', 'NCERRORCVC', 'NCERRORED', 'NCSTATUS', 'ORDERID',
        'PAYID', 'PM', 'SCO_CATEGORY', 'SCORING', 'STATUS', 'SUBSCRIPTION_ID', 'TRXDATE','VC');

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var string
     */
    protected $shaSign;

    /**
     * @param array $httpRequest Typically $_REQUEST
     * @throws \InvalidArgumentException
     */
    public function __construct(array $httpRequest)
    {
        // use uppercase internally
        $httpRequest = array_change_key_case($httpRequest, CASE_UPPER);

        // set sha sign
        $this->shaSign = $this->extractShaSign($httpRequest);

        // filter request for Ogone parameters
        $this->parameters = $this->filterRequestParameters($httpRequest);
    }

    /**
     * Filter http request parameters
     * @param array $requestParameters
     * @return array
     */
    protected function filterRequestParameters(array $requestParameters)
    {
        // filter request for Ogone parameters
        return array_intersect_key($requestParameters, array_flip($this->ogoneFields));
    }

    /**
     * Set Ogone SHA sign
     * @param array $parameters
     * @throws \InvalidArgumentException
     */
    protected function extractShaSign(array $parameters)
    {
        if (!array_key_exists(self::SHASIGN_FIELD, $parameters) || $parameters[self::SHASIGN_FIELD] == '') {
            throw new InvalidArgumentException('SHASIGN parameter not present in parameters.');
        }
        return $parameters[self::SHASIGN_FIELD];
    }

    /**
     * Retrieves a response parameter
     * @param string $key
     * @throws \InvalidArgumentException
     */
    public function getParam($key)
    {
        if (method_exists($this, 'get'.$key)) {
            return $this->{'get'.$key}();
        }

        // always use uppercase
        $key = strtoupper($key);

        if (!array_key_exists($key, $this->parameters)) {
            throw new InvalidArgumentException('Parameter ' . $key . ' does not exist.');
        }

        return $this->parameters[$key];
    }

    /**
     * Get all parameters + SHASIGN
     * @return array
     */
    public function toArray()
    {
        return $this->parameters + array('SHASIGN' => $this->shaSign);
    }
}
