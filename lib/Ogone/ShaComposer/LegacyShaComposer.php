<?php
namespace Ogone\ShaComposer;

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
	 * @param string $passphrase
	 */
	public function __construct(Passphrase $passphrase)
	{
		$this->passphrase = $passphrase;
	}

	public function compose(array $parameters)
	{
		$parameters = array_change_key_case($parameters, CASE_LOWER);

		return strtoupper(sha1(implode('', array(
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