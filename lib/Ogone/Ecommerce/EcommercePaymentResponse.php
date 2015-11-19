<?php
/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Ecommerce;

use Ogone\AbstractPaymentResponse;
use Ogone\ShaComposer\ShaComposer;

class EcommercePaymentResponse extends AbstractPaymentResponse
{

    /**
     * Checks if the response is valid
     * @param ShaComposer $shaComposer
     * @return bool
     */
    public function isValid(ShaComposer $shaComposer)
    {
        return $shaComposer->compose($this->parameters) == $this->shaSign;
    }
}
