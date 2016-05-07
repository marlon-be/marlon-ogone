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

        $defaultParameters = [
            'orderid' => '',
            'amount' => '',
            'currency' => '',
            'pspid' => '',
            'pm' => '',
            'acceptance' => '',
            'status' => '',
            'cardno' => '',
            'payid' => '',
            'ncerror' => '',
            'brand' => '',
            'passphrase' => $this->passphrase,
        ];

        $finalParameters = array_merge($defaultParameters, $parameters);

        return strtoupper(hash($this->hashAlgorithm, implode('', [
            !empty($finalParameters['orderid']) ? $finalParameters['orderid'] : null,
            !empty($finalParameters['amount']) ? $finalParameters['amount'] : null,
            !empty($finalParameters['currency']) ? $finalParameters['currency'] : null,
            !empty($finalParameters['pspid']) ? $finalParameters['pspid'] : null,
            !empty($finalParameters['pm']) ? $finalParameters['pm'] : null,
            !empty($finalParameters['acceptance']) ? $finalParameters['acceptance'] : null,
            !empty($finalParameters['status']) ? $finalParameters['status'] : null,
            !empty($finalParameters['cardno']) ? $finalParameters['cardno'] : null,
            !empty($finalParameters['payid']) ? $finalParameters['payid'] : null,
            !empty($finalParameters['ncerror']) ? $finalParameters['ncerror'] : null,
            !empty($finalParameters['brand']) ? $finalParameters['brand'] : null,
            !empty($finalParameters['passphrase']) ? $finalParameters['passphrase'] : null,
        ])));
    }
}
