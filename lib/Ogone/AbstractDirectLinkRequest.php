<?php
/*
 * @author Nicolas Clavaud <nicolas@lrqdo.fr>
 */

namespace Ogone;

use InvalidArgumentException;

abstract class AbstractDirectLinkRequest extends AbstractRequest
{

    public function setUserId($userid)
    {
        if (strlen($userid) < 2) {
            throw new InvalidArgumentException("User ID is too short");
        }
        $this->parameters['userid'] = $userid;
    }

    public function setPassword($password)
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException("Password is too short");
        }
        $this->parameters['pswd'] = $password;
    }

    public function setPayId($payid)
    {
        $this->parameters['payid'] = $payid;
    }

    public function setOrderId($orderid)
    {
        $this->parameters['orderid'] = $orderid;
    }

    protected function getRequiredFieldGroups()
    {
        return array(
            array('payid', 'orderid'),
        );
    }
}
