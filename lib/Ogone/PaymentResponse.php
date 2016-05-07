<?php

namespace Ogone;

interface PaymentResponse extends Response
{
    /**
     * @var int
     */
    const STATUS_INCOMPLETE_OR_INVALID = 0;
    /**
     * @var int
     */
    const STATUS_CANCELLED_BY_CLIENT = 1;
    /**
     * @var int
     */
    const STATUS_AUTHORISATION_REFUSED = 2;
    /**
     * @var int
     */
    const STATUS_AUTHORISED = 5;
    /**
     * @var int
     */
    const STATUS_AUTHORISED_AND_CANCELED = 6;
    /**
     * @var int
     */
    const STATUS_PAYMENT_DELETED = 7;
    /**
     * @var int
     */
    const STATUS_REFUND = 8;
    /**
     * @var int
     */
    const STATUS_PAYMENT_REQUESTED = 9;
    /**
     * @var int
     */
    const STATUS_AUTHORISATION_WAITING = 51;
    /**
     * @var int
     */
    const STATUS_AUTHORISATION_NOT_KNOWN = 52;
    /**
     * @var int
     */
    const STATUS_PAYMENT = 91;
    /**
     * @var int
     */
    const STATUS_PAYMENT_UNCERTAIN = 92;
    /**
     * @var int
     */
    const STATUS_PAYMENT_REFUSED = 93;

    /**
     * When the payment is set to paid manually
     * @var int
     */
    const STATUS_PAYMENT_BY_MERCHANT = 95;
}
