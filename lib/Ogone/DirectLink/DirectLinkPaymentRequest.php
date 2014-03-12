<?php
/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\DirectLink;

use Ogone\AbstractPaymentRequest;
use Ogone\ShaComposer\ShaComposer;
use InvalidArgumentException;

class DirectLinkPaymentRequest extends AbstractPaymentRequest {

    const TEST = "https://secure.ogone.com/ncol/test/orderdirect.asp";
    const PRODUCTION = "https://secure.ogone.com/ncol/prod/orderdirect.asp";

    public function __construct(ShaComposer $shaComposer)
    {
        $this->shaComposer = $shaComposer;
        $this->ogoneUri = self::TEST;
    }

    public function getRequiredFields()
    {
        return array(
            'pspid', 'currency', 'amount', 'orderid', 'userid', 'pswd'
        );
    }

    public function getValidOgoneUris()
    {
        return array(self::TEST, self::PRODUCTION);
    }

    public function setUserId($userid)
    {
        if(strlen($userid) < 8) {
            throw new InvalidArgumentException("User ID is too short");
        }
        $this->parameters['userid'] = $userid;
    }

    /** Alias for setPswd() */
    public function setPassword($password)
    {
        $this->setPswd($password);
    }

    public function setPswd($password)
    {
        if(strlen($password) < 8) {
            throw new InvalidArgumentException("Password is too short");
        }
        $this->parameters['pswd'] = $password;
    }

    public function setAlias(Alias $alias)
    {
        $this->parameters['alias'] = $alias->__toString();
    }

    public function setAsRecurrentPayment()
    {
        $this->parameters['eci'] = 9;
    }
}
