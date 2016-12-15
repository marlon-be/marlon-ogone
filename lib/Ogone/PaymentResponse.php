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
     * The authorisation deletion will be processed offline.
     *
     * @var int
     */
    const STATUS_PENDING_AUTHORISATION_CANCELLATION = 61;

    /**
     * A technical problem arose during the authorisation deletion process, giving an unpredictable result.
     *
     * The merchant can contact the acquirer helpdesk to establish the precise status of the payment or wait until we
     * have updated the status in our system.
     *
     * Usually NCERROR will contain a 200x error
     *
     * @var int
     */
    const STATUS_UNKNOWN_AUTHORISATION_CANCELLATION = 62;

    /**
     * A technical problem arose. Usually NCERROR will contain a 300x error
     *
     * @var int
     */
    const STATUS_DENIED_AUTHORISATION_CANCELLATION = 63;

    /**
     * Waiting for payment cancellation/deletion
     *
     * @var int
     */
    const STATUS_PENDING_PAYMENT_CANCELLATION = 71;

    /**
     * @var int
     */
    const STATUS_UNKNOWN_PAYMENT_CANCELLATION = 72;

    /**
     * @var int
     */
    const STATUS_DENIED_PAYMENT_CANCELLATION = 73;

    /**
     * @var int
     */
    const STATUS_PAYMENT_CANCELLED = 74;

    /**
     * Waiting for refund of the payment
     *
     * @var int
     */
    const STATUS_PENDING_REFUND = 81;

    /**
     * When the payment is set to paid manually
     * @var int
     */
    const STATUS_PAYMENT_BY_MERCHANT = 95;
}
