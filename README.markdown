# MARLON OGONE #

## Overview ##

- Create a PaymentRequest, containing all the info needed by Ogone.
- Generate  a form
- Submit it to Ogone (client side)
- Receive a PaymentResponse back from Ogone (as a HTTP Request)

Both PaymentRequest and PaymentResponse are authenticated by comparing the SHA sign, 
which is a hash of the parameters and a secret passphrase. You can create the hash using a ShaComposer.  

# SHA Composers #

Ogone provides 2 methods to generate a SHA sign:

- "Main parameters only"

  ![Main parameters only](http://github.com/marlon-be/marlon-ogone/raw/master/documentation/images/ogone_security_legacy.png)
  
  Implementation using this library is trivial:

```php
  <?php
	use Ogone\ShaComposer\LegacyShaComposer;
	$shaComposer = new LegacyShaComposer($passphrase);
```

- "Each parameter followed by the passphrase"
  This method allows you to select one of the following encryption methods: SHA-1 (default), SHA-256 and SHA-512.

  ![Each parameter followed by the passphrase](http://github.com/marlon-be/marlon-ogone/raw/master/documentation/images/ogone_security_allparameters_sha1_utf8.png)
  
  Implementation using this library is trivial:
  
```php
  <?php
	use Ogone\ShaComposer\AllParametersShaComposer;
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional
```  

This library currently supports both the legacy method "Main parameters only" and the new method "Each parameter followed by the passphrase" with SHA-1 encryption.

# PaymentRequest and FormGenerator #


	<?php

	use Ogone\PaymentRequest;
	use Ogone\FormGenerator;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
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

	$passphrase = new Passphrase('my-sha-out-passphrase-defined-in-ogone-interface');
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