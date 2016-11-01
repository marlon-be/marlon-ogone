<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\ShaComposer;

use Ogone\HashAlgorithm;
use Ogone\Passphrase;

/**
 * SHA string composition the "old way", using only the "main" parameters
 * @deprecated Use AllParametersShaComposer wherever possible
 */
class LegacyShaComposer implements ShaComposer
{
    /**
     * @var string Passphrase
     */
    private $passphrase;

    /**
     * @var HashAlgorithm
     */
    private $hashAlgorithm;

    /**
     * @param Passphrase $passphrase
     * @param HashAlgorithm $hashAlgorithm
     */
    public function __construct(Passphrase $passphrase, HashAlgorithm $hashAlgorithm = null)
    {
        $this->passphrase = $passphrase;
        $this->hashAlgorithm = $hashAlgorithm ?: new HashAlgorithm(HashAlgorithm::HASH_SHA1);
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function compose(array $parameters)
    {
        $parameters = array_change_key_case($parameters, CASE_LOWER);

        return strtoupper(hash($this->hashAlgorithm, implode('', array(
            $parameters['orderid'],
            $parameters['currency'],
            $parameters['amount'],
            $parameters['pm'],
            $parameters['acceptance'],
            $parameters['status'],
            $parameters['cardno'],
            $parameters['payid'],
            $parameters['ncerror'],
            $parameters['brand'],
            $this->passphrase
        ))));
    }
}
