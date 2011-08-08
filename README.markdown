# MARLON OGONE #

## Overview ##

- Create a PaymentRequest, containing all the info needed by Ogone.
- Generate  a form
- Submit it to Ogone (client side)
- Receive a PaymentResponse back from Ogone (as a HTTP Request)

Both PaymentRequest and PaymentResponse are authenticated by comparing a the shasign, 
which is a hash of the parameters and a secret passphrase. You can create the hash using a ShaComposer.  

# PaymentRequest and FormGenerator #

	<?php

	use Ogone\PaymentRequest;
	use Ogone\FormGenerator;

	$passphrase = 'my-sha-in-passphrase-defined-in-ogone-interface';
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional
	
	$paymentRequest = new PaymentRequest($shaComposer);
	
	// Optionally set Ogone uri, defaults to TEST account
	//$paymentRequest->setOgoneUri(PaymentRequest::PRODUCTION);

	// Set various params:
	$paymentRequest->setOrderid('123456');
	$paymentRequest->setAmount('150'); // in cents
	$paymentRequest->setCurrency('EUR');
	// ...

	$paymentRequest->validate();

	$formGenerator = new SimpleFormGenerator; 
	$html = $formGenerator->render($paymentRequest);
	// Or use your own generator. Or pass $paymentRequest to a view


# PaymentResponse #

  	<?php
	use Ogone\PaymentResponse;
	use Ogone\ShaComposer\AllParametersShaComposer;

	// ...

	$paymentResponse = new PaymentResponse($_REQUEST);

	$passphrase = 'my-sha-out-passphrase-defined-in-ogone-interface';
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaOutParameterFilter); //optional
	
	if($paymentResponse->isValid($shaComposer) && $paymentResponse->isSuccessful()) {
		// handle payment confirmation
	}
	else {
		// perform logic when the validation fails
	}


## SHA-OUT with "old" hashing algorithm ##

You can use the legacy SHA composer, which only uses some parameters to create the Sha, instead of all of them: 

 	<?php
	use Ogone\ShaComposer\LegacyShaComposer;
	$shaComposer = new LegacyShaComposer($passphrase);



# TODO's #

- @todo Move webshop\documentation\payment_types\ogone.rst to this project