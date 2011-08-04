# Ogone Confirmation Response wrapper #

## Usage ##

  	<?php
	use Ogone\ConfirmationResponse;
	use Ogone\ShaComposer\AllParametersShaComposer;

	// ...

	$confirmationResponse = new ConfirmationResponse($_REQUEST);

	$passphrase = 'my-sha-out-passphrase-defined-in-ogone-interface';
	$shaComposer = new AllParametersShaComposer($passphrase);
	
	if($confirmationResponse->isValid($shaComposer) && $confirmationResponse->isSuccessful())
	{
		// handle payment confirmation
	}
	else
	{
		// perform logic when the validation fails
	}

## SHA-OUT with "old" hashing algorithm ##

Alternatively, you can use the old style SHA composer: 

 	<?php
	use Ogone\ShaComposer\MainParametersShaComposer;
	$shaComposer = new MainParametersShaComposer($passphrase);

## TODO's ##

- @todo Move webshop\documentation\payment_types\ogone.rst to this project