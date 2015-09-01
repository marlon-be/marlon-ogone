<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone\DirectLink;

use Ogone\AbstractResponse;

class DirectLinkMaintenanceResponse extends DirectLinkPaymentResponse
{

    public function isSuccessful()
    {
        return (0 == $this->getParam('NCERROR'));
    }

    protected function filterRequestParameters(array $httpRequest)
    {
        $fields = array(
            'NCERRORPLUS',
            'PAYIDSUB',
        );

        return array_intersect_key($httpRequest, array_flip(array_merge($this->ogoneFields, $fields)));
    }
}
