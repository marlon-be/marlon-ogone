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
}
