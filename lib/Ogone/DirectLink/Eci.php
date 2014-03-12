<?php

namespace Ogone\DirectLink;

/**
 * Class Eci
 *
 * Electronic Commerce Indicator. The ECI indicates the security level at which the payment information is processed between the cardholder and merchant.
 * A default ECI value can be set in the Technical Information page. An ECI value sent along in the transaction, will overwrite the default ECI value.
 * It is the merchant's responsibility to give correct ECI values for the transactions. For e-Commerce, our system sets ECI value 5, 6 or 7 depending on the 3-D Secure authentication result.
 */
class Eci
{
    /** The merchant took the customer's credit card and swiped it through a machine that read the magnetic strip data of the card. */
    const SWIPED = 0;

    /** The merchant received the customer's financial details over the phone or via fax/mail, but does not have the customer's card at hand. */
    const MANUALLY_KEYED = 1;

    /** The customer's first transaction was a Mail Order / Telephone Order transaction, i.e. the customer gave his financial details over the phone or via mail/fax to the merchant. The merchant either stored the details himself or had these details stored in our system using an Alias and is performing another transaction for the same customer (recurring transaction). */
    const RECURRENT_MOTO = 2;

    /** Partial payment of goods/services that have already been delivered, but will be paid for in several spread payments. */
    const INSTALLMENT_PAYMENTS = 3;

    /** The customer is physically present in front of the merchant. The merchant has the customer's card at hand. The card details are manually entered, the card is not swiped through a machine. */
    const MANUALLY_KEYED_CARD_PRESENT = 4;

    /** The cardholder's 3-D Secure identification was successful, i.e. there was a full authentication. (Full thumbs up) */
    const CARDHOLDER_IDENTIFICATION_SUCCESSFUL = 5;

    /** Merchant supports identification but not cardholder, The merchant has a 3-D Secure contract, but the cardholder's card is not 3-D Secure or is 3-D Secure but the cardholder is not yet in possession of the PIN (Half thumbs up). Conditional payment guarantee rules apply. */
    const MERCHANT_IDENTIFICATION_3DSECURE = 6;

    /** The merchant received the customer's financial details via a secure (SSL encrypted) website (either the merchant's website or our secure platform). */
    const ECOMMERCE_WITH_SSL = 7;

    /** The customer's first transaction was an e-Commerce transaction, i.e. the customer entered his financial details himself on a secure website (either the merchant's website or our secure platform). The merchant either stored the details himself or had these details stored in our system using an Alias and is now performing another transaction for the same customer (recurring transaction), using the Alias details. */
    const ECOMMERCE_RECURRING = 9;

    /** @var int */
    protected $code;

    public function __construct($eciCode)
    {
        $this->code = $eciCode;
    }

    public function __toString()
    {
        return (string) $this->code;
    }
}
