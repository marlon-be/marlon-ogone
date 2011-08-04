<?php
namespace Ogone\ShaComposer;

/**
 * SHA string composition the "new way", using all parameters in the ogone response
 */
class AllParametersShaComposer extends AbstractShaComposer
{
	public function compose(array $responseParameters)
	{
		// use lowercase internally
		$responseParameters = array_change_key_case($responseParameters, CASE_LOWER);

		// sort parameters
		ksort($responseParameters);

		// compose SHA string
		$shaString = '';
		foreach($responseParameters as $key => $value)
		{
			if($value !== null) {
				$shaString .= strtoupper($key) . '=' . trim($value) . $this->passphrase;
			}
		}

		return strtoupper(sha1($shaString));
	}
}