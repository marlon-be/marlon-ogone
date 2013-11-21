<?php

namespace Ogone;

interface PaymentRequest extends Request
{
    /** @var string */
    const SHASIGN_FIELD = 'SHASIGN';
}
