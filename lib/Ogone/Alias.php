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

use InvalidArgumentException;

class Alias {

    /** @var string */

    private $alias;

    public function __construct($alias = '')
    {
        if(strlen($alias) > 50) {
            throw new InvalidArgumentException("Alias is too long");
        }

        if(preg_match('/[^a-zA-Z0-9_-]/', $alias)) {
            throw new InvalidArgumentException("Alias cannot contain special characters");
        }

        $this->alias = $alias;
    }

    public function __toString()
    {
        return (string) $this->alias;
    }
} 