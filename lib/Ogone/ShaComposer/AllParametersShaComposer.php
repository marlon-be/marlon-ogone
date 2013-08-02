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

use Ogone\ParameterFilter\GeneralParameterFilter;
use Ogone\Passphrase;
use Ogone\ParameterFilter\ParameterFilter;

/**
 * SHA string composition the "new way", using all parameters in the ogone response
 */
class AllParametersShaComposer implements ShaComposer
{
	/** @var array of ParameterFilter */
	private $parameterFilters;

	/**
	 * @var string Passphrase
	 */
	private $passphrase;
    /**
     * @var string
     */
    private $hashAlgorithm;

	/**
	 * @param Passphrase $passphrase
     * @param string $hashAlgorithm
	 */
	public function __construct(Passphrase $passphrase, $hashAlgorithm = 'sha1')
	{
		$this->passphrase = $passphrase;

		$this->addParameterFilter(new GeneralParameterFilter);

        $this->hashAlgorithm = $hashAlgorithm;
	}

	public function compose(array $parameters)
	{
		foreach($this->parameterFilters as $parameterFilter) {
			$parameters = $parameterFilter->filter($parameters);
		}

		ksort($parameters);

		// compose SHA string
		$shaString = '';
		foreach($parameters as $key => $value) {
			$shaString .= $key . '=' . $value . $this->passphrase;
		}

		return strtoupper(hash($this->hashAlgorithm, $shaString));
	}

	public function addParameterFilter(ParameterFilter $parameterFilter)
	{
		$this->parameterFilters[] = $parameterFilter;
	}

    /**
     * Sets the hash algorithm.
     *
     * @param string $hashAlgorithm
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setHashAlgorithm($hashAlgorithm)
    {
        if (! in_array($hashAlgorithm, array('sha1', 'sha256', 'sha512'))) {
            throw new \InvalidArgumentException(
                $hashAlgorithm . ' is not supported, only sha1, sha256 and sha512 are allowed.'
            );
        }

        $this->hashAlgorithm = $hashAlgorithm;

        return $this;
    }
}
