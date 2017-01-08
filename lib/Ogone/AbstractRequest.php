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
use Ogone\ShaComposer\ShaComposer;

abstract class AbstractRequest implements Request
{
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
        'cardno', 'cvc', 'ed', 'ownerzip', 'owneraddress', 'ownercty', 'ownertown', 'ownertelno',
        'homeurl', 'catalogurl', 'accepturl', 'declineurl', 'exceptionurl', 'cancelurl', 'backurl',
        'complus', 'paramplus', 'pm', 'brand', 'title', 'bgcolor', 'txtcolor', 'tblbgcolor',
        'tbltxtcolor', 'buttonbgcolor', 'buttontxtcolor', 'logo', 'fonttype', 'tp', 'paramvar',
        'alias', 'aliasoperation', 'aliasusage', 'aliaspersistedafteruse', 'device', 'pmlisttype',
        'ecom_payment_card_verification', 'operation', 'withroot', 'remote_addr', 'rtimeout',
        'pmlist', 'exclpmlist', 'creditdebit', 'userid',
        // DirectLink with 3-D Secure: Extra request parameters.
        // https://payment-services.ingenico.com/int/en/ogone/support/guides/integration%20guides/directlink-3-d/3-d-transaction-flow-via-directlink#extrarequestparameters
        'flag3d', 'http_accept', 'http_user_agent', 'win3ds',
        // Optional integration data: Delivery and Invoicing data.
        // https://payment-services.ingenico.com/int/en/ogone/support/guides/integration%20guides/additional-data/delivery-and-invoicing-data
        'civility', 'cuid', 'ecom_billto_postal_city', 'ecom_billto_postal_countrycode',
        'ecom_billto_postal_name_first', 'ecom_billto_postal_name_last', 'ecom_billto_postal_postalcode',
        'ecom_billto_postal_street_line1', 'ecom_billto_postal_street_number', 'ecom_shipto_dob',
        'ecom_shipto_online_email', 'ecom_shipto_postal_city', 'ecom_shipto_postal_countrycode',
        'ecom_shipto_postal_name_first', 'ecom_shipto_postal_name_last', 'ecom_shipto_postal_name_prefix',
        'ecom_shipto_postal_postalcode', 'ecom_shipto_postal_state', 'ecom_shipto_postal_street_line1',
        'ecom_shipto_postal_street_number', 'ordershipcost', 'ordershipmeth', 'ordershiptaxcode',
        // Optional integration data: Order data ("ITEM" parameters).
        // https://payment-services.ingenico.com/int/en/ogone/support/guides/integration%20guides/additional-data/order-data
        'itemattributes*', 'itemcategory*', 'itemcomments*', 'itemdesc*', 'itemdiscount*',
        'itemid*', 'itemname*', 'itemprice*', 'itemquant*', 'itemquantorig*',
        'itemunitofmeasure*', 'itemvat*', 'itemvatcode*', 'itemweight*',
        // Optional integration data: Travel data.
        // https://payment-services.ingenico.com/int/en/ogone/support/guides/integration%20guides/additional-data/travel-data
        'datatype', 'aiairname', 'aitinum', 'aitidate', 'aiconjti', 'aipasname',
        'aiextrapasname*', 'aichdet', 'aiairtax', 'aivatamnt', 'aivatappl', 'aitypch',
        'aieycd', 'aiirst', 'aiorcity*', 'aiorcityl*', 'aidestcity*', 'aidestcityl*',
        'aistopov*', 'aicarrier*', 'aibookind*', 'aiflnum*', 'aifldate*', 'aiclass*',
        // Subscription Manager.
        // https://payment-services.ingenico.com/int/en/ogone/support/guides/integration%20guides/subscription-manager/via-e-commerce-and-directlink#input
        'subscription_id', 'sub_amount', 'sub_com', 'sub_orderid', 'sub_period_unit',
        'sub_period_number', 'sub_period_moment', 'sub_startdate', 'sub_enddate',
        'sub_status', 'sub_comment',
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
        if (strlen($pspid) > 30) {
            throw new InvalidArgumentException("PSPId is too long");
        }
        $this->parameters['pspid'] = $pspid;
    }

    public function setSecure()
    {
      $this->parameters['win3ds'] = 'MAINW';
    }

    /**
     * ISO code eg nl_BE
     */
    public function setLanguage($language)
    {
        if (!array_key_exists($language, $this->allowedlanguages)) {
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

    public function setHomeurl($homeurl)
    {
        if (!empty($homeurl)) {
            $this->validateUri($homeurl);
        }
        $this->parameters['homeurl'] = $homeurl;
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

    public function validate()
    {
        foreach ($this->getRequiredFields() as $field) {
            if (empty($this->parameters[$field])) {
                throw new RuntimeException("$field can not be empty");
            }
        }
    }

    protected function validateUri($uri)
    {
        if (!filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Uri is not valid");
        }
        if (strlen($uri) > 200) {
            throw new InvalidArgumentException("Uri is too long");
        }
    }

    protected function validateOgoneUri($uri)
    {
        $this->validateUri($uri);

        if (!in_array($uri, $this->getValidOgoneUris())) {
            throw new InvalidArgumentException('No valid Ogone url');
        }
    }

    /**
     * Allows setting ogone parameters that don't have a setter -- usually only
     * the unimportant ones like bgcolor, which you'd call with setBgcolor()
     *
     * @param $method
     * @param $args
     */
    public function __call($method, $args)
    {
        if (substr($method, 0, 3) == 'set') {
            $field = strtolower(substr($method, 3));
            // Also search for numbered fields, like ITEMID1, ITEMID2 etc.
            $numbered_field = preg_replace('/\d+$/', '*', $field);
            if (in_array($field, $this->ogoneFields) || in_array($numbered_field, $this->ogoneFields)) {
                $this->parameters[$field] = $args[0];
                return;
            }
        }

        if (substr($method, 0, 3) == 'get') {
            $field = strtolower(substr($method, 3));
            if (array_key_exists($field, $this->parameters)) {
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
