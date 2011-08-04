<?php
namespace Ogone\ShaComposer;

/**
 * SHA string composition the "old way", using only the "main" parameters
 * @deprecated Use AllParametersShaComposer wherever possible
 */
class MainParametersShaComposer extends AbstractShaComposer
{
	public function compose(array $responseParameters)
	{
		// use lowercase internally
		$responseParameters = array_change_key_case($responseParameters, CASE_LOWER);

		return strtoupper(sha1(implode('', array(
			$responseParameters['orderid'],
			$responseParameters['currency'],
			$responseParameters['amount'],
			$responseParameters['pm'],
			$responseParameters['acceptance'],
			$responseParameters['status'],
			$responseParameters['cardno'],
			$responseParameters['payid'],
			$responseParameters['ncerror'],
			$responseParameters['brand'],
			$this->passphrase
		))));
	}
}