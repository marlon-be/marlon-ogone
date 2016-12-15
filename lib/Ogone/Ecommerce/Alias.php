<?php
namespace Ogone\Ecommerce;

use InvalidArgumentException;

class Alias
{

    const OPERATION_BY_MERCHANT = 'BYMERCHANT';
    const OPERATION_BY_PSP = 'BYPSP';

    /** @var string */
    private $aliasOperation;

    /** @var string */
    private $aliasUsage;

    /** @var string */
    private $alias;

    public function __construct($alias, $aliasOperation = self::OPERATION_BY_MERCHANT, $aliasUsage = null)
    {
        if (strlen($alias) > 50) {
            throw new InvalidArgumentException("Alias is too long");
        }

        if (preg_match('/[^a-zA-Z0-9_-]/', $alias)) {
            throw new InvalidArgumentException("Alias cannot contain special characters");
        }

        $this->aliasOperation = $aliasOperation;
        $this->aliasUsage = $aliasUsage;
        $this->alias = $alias;
    }

    public function operationByMerchant()
    {
        $this->aliasOperation = self::OPERATION_BY_MERCHANT;
    }

    public function operationByPsp()
    {
        $this->aliasOperation = self::OPERATION_BY_PSP;
    }

    public function getAliasOperation()
    {
        return $this->aliasOperation;
    }

    public function getAliasUsage()
    {
        return $this->aliasUsage;
    }

    public function setAliasUsage($aliasUsage)
    {
        $this->aliasUsage = $aliasUsage;
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
