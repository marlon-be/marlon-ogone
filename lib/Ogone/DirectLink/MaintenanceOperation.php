<?php
namespace Ogone\DirectLink;


class MaintenanceOperation
{
    const OPERATION_AUTHORISATION_RENEW = 'REN';
    const OPERATION_AUTHORISATION_DELETE = 'DEL';
    const OPERATION_AUTHORISATION_DELETE_AND_CLOSE = 'DES';
    const OPERATION_CAPTURE_PARTIAL = 'SAL';
    const OPERATION_CAPTURE_LAST_OR_FULL = 'SAS';
    const OPERATION_REFUND_PARTIAL = 'RFD';
    const OPERATION_REFUND_LAST_OR_FULL = 'RFS';

    protected $operation;

    public function __construct($operation)
    {
        if(!in_array($operation, self::getAllAvailableOperations())) {
            throw new \InvalidArgumentException('Unknown Operation: ' . $operation);
        }

        $this->operation = $operation;
    }

    public function equals(MaintenanceOperation $other)
    {
        return $this->operation === $other->operation;
    }

    public function __toString()
    {
        return (string) $this->operation;
    }

    private function getAllAvailableOperations()
    {
        return array(
            self::OPERATION_AUTHORISATION_RENEW,
            self::OPERATION_AUTHORISATION_DELETE,
            self::OPERATION_AUTHORISATION_DELETE_AND_CLOSE,
            self::OPERATION_CAPTURE_PARTIAL,
            self::OPERATION_CAPTURE_LAST_OR_FULL,
            self::OPERATION_REFUND_PARTIAL,
            self::OPERATION_REFUND_LAST_OR_FULL,
        );
    }
} 