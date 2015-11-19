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

/**
 * Ogone Passphrase Value Object
 */
final class Passphrase
{
    /**
     * @var string
     */
    private $passphrase;

    /** @@codeCoverageIgnore */
    public function __construct($passphrase)
    {
        if (!is_string($passphrase)) {
            throw new \InvalidArgumentException("String expected");
        }
        $this->passphrase = $passphrase;
    }

    /**
     * String representation
     */
    public function __toString()
    {
        return (string) $this->passphrase;
    }
}
