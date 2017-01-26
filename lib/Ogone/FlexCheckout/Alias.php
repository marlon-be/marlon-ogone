<?php namespace Ogone\FlexCheckout;

use InvalidArgumentException;

class Alias
{
    /** @var string */
    private $alias;

    public function __construct($alias)
    {
        if (preg_match('/[^a-zA-Z0-9_-]/', $alias)) {
            throw new InvalidArgumentException("Alias cannot contain special characters");
        }

        $this->alias = $alias;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    public function __toString()
    {
        return $this->alias;
    }
}
