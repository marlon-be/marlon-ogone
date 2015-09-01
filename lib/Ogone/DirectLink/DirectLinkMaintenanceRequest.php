<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\DirectLink;

use Ogone\AbstractDirectLinkRequest;
use Ogone\ShaComposer\ShaComposer;
use InvalidArgumentException;

class DirectLinkMaintenanceRequest extends AbstractDirectLinkRequest
{

    const TEST = "https://secure.ogone.com/ncol/test/maintenancedirect.asp";
    const PRODUCTION = "https://secure.ogone.com/ncol/prod/maintenancedirect.asp";

    const OPERATION_AUTHORISATION_RENEW = 'REN';
    const OPERATION_AUTHORISATION_DELETE = 'DEL';
    const OPERATION_AUTHORISATION_DELETE_AND_CLOSE = 'DES';
    const OPERATION_CAPTURE_PARTIAL = 'SAL';
    const OPERATION_CAPTURE_LAST_OR_FULL = 'SAS';
    const OPERATION_REFUND_PARTIAL = 'RFD';
    const OPERATION_REFUND_LAST_OR_FULL = 'RFS';

    public function __construct(ShaComposer $shaComposer)
    {
        $this->shaComposer = $shaComposer;
        $this->ogoneUri = self::TEST;
    }

    public function getRequiredFields()
    {
        return array(
            'pspid',
            'userid',
            'pswd',
            'operation',
        );
    }

    public function getValidOgoneUris()
    {
        return array(self::TEST, self::PRODUCTION);
    }

    public function setAmount($amount)
    {
        if (!is_int($amount)) {
            throw new InvalidArgumentException("Amount should be an integer");
        }

        $this->parameters['amount'] = $amount;
    }

    public function setOperation($operation)
    {
        if (!in_array($operation, $this->getValidOperations())) {
            throw new InvalidArgumentException("Invalid operation");
        }
        $this->parameters['operation'] = $operation;
    }

    private function getValidOperations()
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
