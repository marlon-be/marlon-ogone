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
	 * @param string $passphrase
	 */
	public function __construct(Passphrase $passphrase)
	{
		$this->passphrase = $passphrase;

		$this->addParameterFilter(new GeneralParameterFilter);
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

		return strtoupper(sha1($shaString));
	}

	public function addParameterFilter(ParameterFilter $parameterFilter)
	{
		$this->parameterFilters[] = $parameterFilter;
	}
}