<?php
/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone;


abstract class AbstractPaymentResponse extends AbstractResponse {

    /**
     * @return int Amount in cents
     */
    public function getAmount()
    {
        $value = trim($this->parameters['AMOUNT']);

        $withoutDecimals = '#^\d*$#';
        $oneDecimal = '#^\d*\.\d$#';
        $twoDecimals = '#^\d*\.\d\d$#';

        if(preg_match($withoutDecimals, $value)) {
            return (int) ($value.'00');
        }

        if(preg_match($oneDecimal, $value)) {
            return (int) (str_replace('.', '', $value).'0');
        }

        if(preg_match($twoDecimals, $value)) {
            return (int) (str_replace('.', '', $value));
        }

        throw new \InvalidArgumentException("Not a valid currency amount");
    }

    public function isSuccessful()
    {
        return in_array($this->getParam('STATUS'), array(5, 9));
    }

} 