# MARLON OGONE #

The best documentation are the unit tests (and a vague understanding of Ogone).
But we are in a friendly mood so here are some pointers: 

# PaymentRequest and FormGenerator #

	<?php

	use Ogone\PaymentRequest;
	use Ogone\FormGenerator;

	$paymentRequest = new PaymentRequest;
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



# TODO's #

- @todo Move webshop\documentation\payment_types\ogone.rst to this project