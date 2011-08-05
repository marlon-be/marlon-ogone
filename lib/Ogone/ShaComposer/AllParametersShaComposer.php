<?php
namespace Ogone\ShaComposer;

/**
 * SHA string composition the "new way", using all parameters in the ogone response
 */
class AllParametersShaComposer extends AbstractShaComposer
{
	public function compose(array $parameters)
	{
		// clean up
		$parameters = array_change_key_case($parameters, CASE_UPPER);
		array_walk($parameters, 'trim');
		$parameters = array_filter($parameters, function($value){ return !is_null($value);});
		ksort($parameters);

		// compose SHA string
		$shaString = '';
		foreach($parameters as $key => $value) {
			$shaString .= $key . '=' . $value . $this->passphrase;
		}

		return strtoupper(sha1($shaString));
	}
}