<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\ParameterFilter;

class ShaOutParameterFilter implements ParameterFilter
{
    private $allowed = array(
        'AAVADDRESS', 'AAVCHECK', 'AAVZIP', 'ACCEPTANCE', 'ALIAS', 'AMOUNT', 'BRAND',
        'CARDNO', 'CCCTY', 'CN', 'COMPLUS', 'CREATION_STATUS', 'CURRENCY', 'CVC', 'CVCCHECK',
        'DCC_COMMPERCENTAGE', 'DCC_CONVAMOUNT', 'DCC_CONVCCY', 'DCC_EXCHRATE', 'DCC_EXCHRATESOURCE',
        'DCC_EXCHRATETS', 'DCC_INDICATOR', 'DCC_MARGINPERCENTAGE', 'DCC_VALIDHOURS',
        'DIGESTCARDNO', 'ECI', 'ED', 'ENCCARDNO', 'IP', 'IPCTY', 'NBREMAILUSAGE',
        'NBRIPUSAGE', 'NBRIPUSAGE_ALLTX', 'NBRUSAGE', 'NCERROR', 'NCERRORCN', 'NCERRORCARDNO', 'NCERRORCVC',
        'NCERRORED','ORDERID', 'PAYID', 'PAYIDSUB', 'PM', 'SCO_CATEGORY', 'SCORING', 'STATUS', 'SUBSCRIPTION_ID',
        'TRXDATE', 'VC',
    );

    public function filter(array $parameters)
    {
        $parameters = array_change_key_case($parameters, CASE_UPPER);
        return array_intersect_key($parameters, array_flip($this->allowed));
    }
}
