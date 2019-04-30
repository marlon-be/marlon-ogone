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
        'AAVADDRESS', 'AAVCHECK', 'AAVMAIL', 'AAVNAME', 'AAVPHONE', 'AAVZIP', 'ACCEPTANCE',
        'ALIAS', 'AMOUNT', 'BIC', 'BIN', 'BRAND', 'CARDNO', 'CCCTY', 'CN', 'COLLECTOR_BIC',
        'COLLECTOR_IBAN', 'COMPLUS', 'CREATION_STATUS', 'CREDITDEBIT', 'CURRENCY', 'CVCCHECK',
        'DCC_COMMPERCENTAGE', 'DCC_CONVAMOUNT', 'DCC_CONVCCY', 'DCC_EXCHRATE','DCC_EXCHRATESOURCE',
        'DCC_EXCHRATETS', 'DCC_INDICATOR', 'DCC_MARGINPERCENTAGE', 'DCC_VALIDHOURS', 'DEVICEID',
        'DIGESTCARDNO', 'ECI', 'ED', 'EMAIL', 'ENCCARDNO', 'FXAMOUNT', 'FXCURRENCY', 'IP',
        'IPCTY', 'MANDATEID', 'MOBILEMODE', 'NBREMAILUSAGE', 'NBRIPUSAGE', 'NBRIPUSAGE_ALLTX',
        'NBRUSAGE', 'NCERROR', 'ORDERID', 'PAYID', 'PAYIDSUB', 'PAYMENT_REFERENCE', 'PM',
        'SCO_CATEGORY', 'SCORING', 'SEQUENCETYPE', 'SIGNDATE', 'STATUS', 'SUBBRAND',
        'SUBSCRIPTION_ID', 'TICKET', 'TRXDATE', 'VC',
    );

    public function filter(array $parameters)
    {
        $parameters = array_change_key_case($parameters, CASE_UPPER);
        return array_intersect_key($parameters, array_flip($this->allowed));
    }
}
