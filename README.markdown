# Ogone PHP library #

This library allows you to easily implement an [Ogone](http://ogone.com) integration into your project.
It provides the necessary components to complete a correct payment flow with the [Ogone](http://ogone.com) platform.

Requirements: 

- PHP 5.3
- network connection between your webserver and the Ogone platform

As always, this is work in progress. Please feel free to fork this project and let them pull requests coming!

## Overview ##

The library complies to the [PSR-0 standard](http://groups.google.com/group/php-standards/web/psr-0-final-proposal), 
so it can be autoloaded using PSR-0 classloaders like the one in Symfony2. See autoload.php for an example.

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
  This method allows you to select one of the following encryption methods: SHA-1 (default), SHA-256 or SHA-512.

  ![Each parameter followed by the passphrase](http://github.com/marlon-be/marlon-ogone/raw/master/documentation/images/ogone_security_allparameters_sha1_utf8.png)
  
  Implementation using this library is trivial:
  
```php
  <?php
	use Ogone\ShaComposer\AllParametersShaComposer;
	$shaComposer = new AllParametersShaComposer($passphrase);
```

This library currently supports both the legacy method "Main parameters only" and the new method "Each parameter followed by the passphrase" with SHA-1 encryption.

# PaymentRequest and FormGenerator #

```php
	<?php

	use Ogone\Passphrase;
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
```

# PaymentResponse #

```php
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
```

# Parameter filters #

ParameterFilters are used to filter the provided parameters (no shit Sherlock).
Both ShaIn- and ShaOutParameterFilters are provided and are based on the parameter lists defined in the Ogone documentation. 
Parameter filtering is optional, but we recommend using them to enforce expected parameters.

# TODO's #

- @todo Move webshop\documentation\payment_types\ogone.rst to this project