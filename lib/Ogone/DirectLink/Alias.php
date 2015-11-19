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

use InvalidArgumentException;

class Alias
{

    /** @var string */
    private $alias;

    /** @var string */
    private $cardName;

    /** @var string */
    private $cardNumber;

    /** @var string */
    private $expiryDate;

    /**
     * @param $alias
     * @param string|null $cardName
     * @param string|null $cardNumber
     * @param string|null $expiryDate
     */
    public function __construct($alias, $cardName = null, $cardNumber = null, $expiryDate = null)
    {
        if (empty($alias)) {
            throw new InvalidArgumentException("Alias cannot be empty");
        }

        if (strlen($alias) > 50) {
            throw new InvalidArgumentException("Alias is too long");
        }

        if (preg_match('/[^a-zA-Z0-9_-]/', $alias)) {
            throw new InvalidArgumentException("Alias cannot contain special characters");
        }

        $this->alias = $alias;
        $this->cardName = $cardName;
        $this->cardNumber = $cardNumber;
        $this->expiryDate = $expiryDate;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getCardName()
    {
        return $this->cardName;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    public function __toString()
    {
        return (string) $this->alias;
    }
}
