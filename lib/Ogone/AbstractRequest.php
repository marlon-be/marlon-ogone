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
use RuntimeException;
use BadMethodCallException;

abstract class AbstractRequest implements Request {

    protected $brandsmap = array(
        'Acceptgiro' => 'Acceptgiro',
        'AIRPLUS' => 'CreditCard',
        'American Express' => 'CreditCard',
        'Aurora' => 'CreditCard',
        'Aurore' => 'CreditCard',
        'Bank transfer' => 'Bank transfer',
        'BCMC' => 'CreditCard',
        'Belfius Direct Net' => 'Belfius Direct Net',
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
    protected $shaComposer;

    protected $ogoneUri;

    protected $parameters = array();

    /** Note this is public to allow easy modification, if need be. */
    public $allowedlanguages = array(
        'en_US' => 'English', 'cs_CZ' => 'Czech', 'de_DE' => 'German',
        'dk_DK' => 'Danish', 'el_GR' => 'Greek', 'es_ES' => 'Spanish',
        'fr_FR' => 'French', 'it_IT' => 'Italian', 'ja_JP' => 'Japanese',
        'nl_BE' => 'Flemish', 'nl_NL' => 'Dutch', 'no_NO' => 'Norwegian',
        'pl_PL' => 'Polish', 'pt_PT' => 'Portugese', 'ru_RU' => 'Russian',
        'se_SE' => 'Swedish', 'sk_SK' => 'Slovak', 'tr_TR' => 'Turkish'
    );

    protected $ogoneFields = array(
        'pspid', 'orderid', 'com', 'amount', 'currency', 'language', 'cn', 'email',
        'cardno', 'cvc', 'ed', 'ownerzip', 'owneraddress', 'ownercty', 'ownertown',
        'ownertelno', 'accepturl', 'declineurl', 'exceptionurl', 'cancelurl', 'backurl',
        'complus', 'paramplus', 'pm', 'brand', 'title', 'bgcolor', 'txtcolor', 'tblbgcolor',
        'tbltxtcolor', 'buttonbgcolor', 'buttontxtcolor', 'logo', 'fonttype', 'tp', 'paramvar'
    );

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
        $this->validateOgoneUri($ogoneUri);
        $this->ogoneUri = $ogoneUri;
    }

    public function setPspid($pspid)
    {
        if(strlen($pspid) > 30) {
            throw new InvalidArgumentException("PSPId is too long");
        }
        $this->parameters['pspid'] = $pspid;
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

    public function setBackurl($backurl)
    {
        $this->validateUri($backurl);
        $this->parameters['backurl'] = $backurl;
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

    public function setBrand($brand)
    {
        if(!array_key_exists($brand, $this->brandsmap)) {
            throw new InvalidArgumentException("Unknown Brand [$brand].");
        }

        $this->setPaymentMethod($this->brandsmap[$brand]);
        $this->parameters['brand'] = $brand;
    }

    public function validate()
    {
        foreach($this->getRequiredFields() as $field)
        {
            if(empty($this->parameters[$field])) {
                throw new RuntimeException("$field can not be empty");
            }
        }
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

    protected function validateOgoneUri($uri)
    {
        $this->validateUri($uri);

        if(!in_array($uri, $this->getValidOgoneUris())) {
            throw new InvalidArgumentException('No valid Ogone url');
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
        return array_change_key_case($this->parameters, CASE_UPPER);
    }

} 
