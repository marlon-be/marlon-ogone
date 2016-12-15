<?php
namespace Ogone\DirectLink;


class PaymentOperation
{
    const REQUEST_FOR_AUTHORISATION = 'RES';
    const REQUEST_FOR_DIRECT_SALE = 'SAL';
    const REFUND = 'RFD';
    const REQUEST_FOR_PRE_AUTHORISATION = 'PAU';

    protected $operation;

    public function __construct($operation)
    {
        if(!in_array($operation, self::getAllAvailableOperations())) {
            throw new \InvalidArgumentException('Unknown Operation: ' . $operation);
        }

        $this->operation = $operation;
    }

    public function equals(PaymentOperation $other)
    {
        return $this->operation === $other->operation;
    }

    public function __toString()
    {
        return (string) $this->operation;
    }

    public function getAllAvailableOperations()
    {
        return array(
            self::REQUEST_FOR_AUTHORISATION,
            self::REQUEST_FOR_DIRECT_SALE,
            self::REFUND,
            self::REQUEST_FOR_PRE_AUTHORISATION
        );
    }
} 