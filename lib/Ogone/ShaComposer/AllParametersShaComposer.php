<?php
namespace Ogone\ShaComposer;

/**
 * SHA string composition the "new way", using all parameters in the ogone response
 */
use Ogone\ParameterFilter\ParameterFilter;

class AllParametersShaComposer extends AbstractShaComposer
{
	/** @var array of ParameterFilter */
	private $parameterFilters;

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