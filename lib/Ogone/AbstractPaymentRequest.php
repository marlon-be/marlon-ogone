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

abstract class AbstractPaymentRequest extends AbstractRequest
{
    const OPERATION_REQUEST_AUTHORIZATION = 'RES';
    const OPERATION_REQUEST_DIRECT_SALE = 'SAL';
    const OPERATION_REFUND = 'RFD';
    const OPERATION_REQUEST_PRE_AUTHORIZATION = 'PAU';

    protected $brandsmap = array(
        'Acceptgiro'            => 'Acceptgiro',
        'AIRPLUS'               => 'CreditCard',
        'American Express'      => 'CreditCard',
        'Aurora'                => 'CreditCard',
        'Aurore'                => 'CreditCard',
        'Bank transfer'         => 'Bank transfer',
        'Bank transfer BE'      => 'Bank transfer BE',
        'Bank transfer DE'      => 'Bank transfer DE',
        'Bank transfer FR'      => 'Bank transfer FR',
        'Bank transfer NL'      => 'Bank transfer NL',
        'BCMC'                  => 'CreditCard',
        'Belfius Direct Net'    => 'Belfius Direct Net',
        'Billy'                 => 'CreditCard',
        'cashU'                 => 'cashU',
        'CB'                    => 'CreditCard',
        'CBC Online'            => 'CBC Online',
        'CENTEA Online'         => 'CENTEA Online',
        'Cofinoga'              => 'CreditCard',
        'Dankort'               => 'CreditCard',
        'Dexia Direct Net'      => 'Dexia Direct Net',
        'Diners Club'           => 'CreditCard',
        'Direct Debits AT'      => 'Direct Debits AT',
        'Direct Debits DE'      => 'Direct Debits DE',
        'Direct Debits NL'      => 'Direct Debits NL',
        'DirectEbankingDE'      => 'DirectEbankingDE',
        'DirectEbankingAT'      => 'DirectEbankingAT',
        'DirectEbankingIT'      => 'DirectEbankingIT',
        'DirectEbankingBE'      => 'DirectEbankingBE',
        'DirectEbankingFR'      => 'DirectEbankingFR',
        'eDankort'              => 'eDankort',
        'EPS'                   => 'EPS',
        'Fortis Pay Button'     => 'Fortis Pay Button',
        'giropay'               => 'giropay',
        'iDEAL'                 => 'iDEAL',
        'ING HomePay'           => 'ING HomePay',
        'InterSolve'            => 'InterSolve',
        'JCB'                   => 'CreditCard',
        'KBC Online'            => 'KBC Online',
        'Maestro'               => 'CreditCard',
        'MaestroUK'             => 'CreditCard',
        'MasterCard'            => 'CreditCard',
        'MiniTix'               => 'MiniTix',
        'MPASS'                 => 'MPASS',
        'NetReserve'            => 'CreditCard',
        'Payment on Delivery'   => 'Payment on Delivery',
        'PAYPAL'                => 'PAYPAL',
        'paysafecard'           => 'paysafecard',
        'PingPing'              => 'PingPing',
        'PostFinance + card'    => 'PostFinance Card',
        'PostFinance e-finance' => 'PostFinance e-finance',
        'PRIVILEGE'             => 'CreditCard',
        'Sofort Uberweisung'    => 'DirectEbanking',
        'Solo'                  => 'CreditCard',
        'TUNZ'                  => 'TUNZ',
        'UATP'                  => 'CreditCard',
        'UNEUROCOM'             => 'UNEUROCOM',
        'VISA'                  => 'CreditCard',
        'Wallie'                => 'Wallie',
    );

    /** Note this is public to allow easy modification, if need be. */
    public $allowedcurrencies = array(
        'AED',
        'ANG',
        'ARS',
        'AUD',
        'AWG',
        'BGN',
        'BRL',
        'BYR',
        'CAD',
        'CHF',
        'CNY',
        'CZK',
        'DKK',
        'EEK',
        'EGP',
        'EUR',
        'GBP',
        'GEL',
        'HKD',
        'HRK',
        'HUF',
        'ILS',
        'ISK',
        'JPY',
        'KRW',
        'LTL',
        'LVL',
        'MAD',
        'MXN',
        'MYR',
        'NOK',
        'NZD',
        'PLN',
        'RON',
        'RUB',
        'SEK',
        'SGD',
        'SKK',
        'THB',
        'TRY',
        'UAH',
        'USD',
        'XAF',
        'XOF',
        'XPF',
        'ZAR'
    );

    public function setOrderid($orderid)
    {
        if (strlen($orderid) > 30) {
            throw new InvalidArgumentException("Orderid cannot be longer than 30 characters");
        }
        if (preg_match('/[^a-zA-Z0-9_-]/', $orderid)) {
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
        if (strlen($com) > 100) {
            throw new InvalidArgumentException("Order description cannot be longer than 100 characters");
        }
        $this->parameters['com'] = $com;
    }

    /**
     * Set amount in cents, eg EUR 12.34 is written as 1234
     */
    public function setAmount($amount)
    {
        if (!is_int($amount)) {
            throw new InvalidArgumentException("Integer expected. Amount is always in cents");
        }
        if ($amount <= 0) {
            throw new InvalidArgumentException("Amount must be a positive number");
        }
        if ($amount >= 1.0E+15) {
            throw new InvalidArgumentException("Amount is too high");
        }
        $this->parameters['amount'] = $amount;
    }

    public function setCurrency($currency)
    {
        if (!in_array(strtoupper($currency), $this->allowedcurrencies)) {
            throw new InvalidArgumentException("Unknown currency");
        }
        $this->parameters['currency'] = $currency;
    }

    public function setEmail($email)
    {
        if (strlen($email) > 50) {
            throw new InvalidArgumentException("Email is too long");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email is invalid");
        }
        $this->parameters['email'] = $email;
    }

    public function setOwnerAddress($owneraddress)
    {
        if (strlen($owneraddress) > 35) {
            throw new InvalidArgumentException("Owner address is too long");
        }
        $this->parameters['owneraddress'] = $owneraddress;
    }

    public function setOwnerZip($ownerzip)
    {
        if (strlen($ownerzip) > 10) {
            throw new InvalidArgumentException("Owner Zip is too long");
        }
        $this->parameters['ownerzip'] = $ownerzip;
    }

    public function setOwnerTown($ownertown)
    {
        if (strlen($ownertown) > 40) {
            throw new InvalidArgumentException("Owner town is too long");
        }
        $this->parameters['ownertown'] = $ownertown;
    }

    /**
     * Alias for setOwnercty
     *
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
        if (!preg_match('/^[A-Z]{2}$/', strtoupper($ownercty))) {
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
        if (strlen($ownertelno) > 30) {
            throw new InvalidArgumentException("Owner phone is too long");
        }
        $this->parameters['ownertelno'] = $ownertelno;
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

    public function setBrand($brand)
    {
        if (!array_key_exists($brand, $this->brandsmap)) {
            throw new InvalidArgumentException("Unknown Brand [$brand].");
        }

        $this->setPaymentMethod($this->brandsmap[$brand]);
        $this->parameters['brand'] = $brand;
    }

    public function setPaymentMethod($paymentMethod)
    {
        $this->setPm($paymentMethod);
    }

    public function setPm($pm)
    {
        if (!in_array($pm, $this->brandsmap)) {
            throw new InvalidArgumentException("Unknown Payment method [$pm].");
        }
        $this->parameters['pm'] = $pm;
    }

    public function setParamvar($paramvar)
    {
        if (strlen($paramvar) < 2 || strlen($paramvar) > 50) {
            throw new InvalidArgumentException("Paramvar must be between 2 and 50 characters in length");
        }
        $this->parameters['paramvar'] = $paramvar;
    }

    /** Alias for setTp */
    public function setDynamicTemplateUri($uri)
    {
        $this->validateUri($uri);
        $this->setTp($uri);
    }
    
    /** Alias for setTp */
    public function setStaticTemplate($tp)
    {
        $this->setTp($tp);
    }

    public function setTp($tp)
    {
        $this->parameters['tp'] = $tp;
    }

    public function setOperation($operation)
    {
        if (!in_array($operation, $this->getValidOperations())) {
            throw new InvalidArgumentException("Invalid operation [$operation].");
        }

        $this->parameters['operation'] = $operation;
    }

    abstract protected function getValidOperations();
}
