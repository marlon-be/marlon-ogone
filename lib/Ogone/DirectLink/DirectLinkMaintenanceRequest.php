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

    public function setOperation(MaintenanceOperation $operation)
    {
        $this->parameters['operation'] = (string) $operation;
    }
}
