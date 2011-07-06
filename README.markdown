# Ogone Confirmation Request wrapper #

## Usage ##

  	<?php
 	
	use Ogone\OgoneConfirmationRequest;
	use Ogone\ShaComposer\AllParametersShaComposer;
	
	// ...
	
	$passphrase = 'my-sha-out-passphrase-defined-in-ogone-interface';
	$shaComposer = new AllParametersShaComposer($passphrase);
	$ogoneConfirmationRequest = new OgoneConfirmationRequest($_REQUEST, $shaComposer);
	
	if($ogoneConfirmationRequest->isValid())
	{
		// handle payment confirmation
	}
	else
	{
		// perform logic when the validation fails
	}