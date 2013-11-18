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

use Ogone\ShaComposer\ShaComposer;
use Ogone\Alias;

class CreateAliasResponse extends AbstractResponse {

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
        // 0 = OK, 1 Not OK, 2 = Alias Updated
        return in_array($this->getParam('STATUS'), array(0, 2));
    }

    public function getAlias()
    {
        $alias = new Alias($this->parameters['ALIAS']);

        return $alias;
    }
} 