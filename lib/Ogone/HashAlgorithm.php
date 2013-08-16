<?php

namespace Ogone;

class HashAlgorithm
{
    const HASH_SHA1 = 'sha1';
    const HASH_SHA256 = 'sha256';
    const HASH_SHA512 = 'sha512';

    /** @var string */
    private $algorithm;

    /**
     * @param $algorithm
     * @throws \InvalidArgumentException
     */
    public function __construct($algorithm)
    {
        if (!in_array($algorithm, array(self::HASH_SHA1, self::HASH_SHA256, self::HASH_SHA512))) {
            throw new \InvalidArgumentException(
                $algorithm . ' is not supported, only sha1, sha256 and sha512 are allowed.'
            );
        }

        $this->algorithm = $algorithm;
    }

    public function __toString()
    {
        return (string) $this->algorithm;
    }
}
