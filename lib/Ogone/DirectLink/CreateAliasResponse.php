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

use Ogone\AbstractResponse;
use Ogone\ShaComposer\ShaComposer;

class CreateAliasResponse extends AbstractResponse
{

    const STATUS_OK = 0;
    const STATUS_NOK = 1;
    const STATUS_UPDATED = 2;

    /**
     * Checks if the response is valid
     * @return bool
     */
    public function isValid(ShaComposer $shaComposer)
    {
        return $shaComposer->compose($this->parameters) == $this->shaSign;
    }

    public function isSuccessful()
    {
        return in_array($this->getParam('STATUS'), array(self::STATUS_OK, self::STATUS_UPDATED));
    }

    public function getAlias()
    {
        return new Alias($this->parameters['ALIAS'], $this->parameters['CN'], $this->parameters['CARDNO'], $this->parameters['ED']);
    }
}
