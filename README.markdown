# Ogone PHP library #

This library allows you to easily implement an [Ogone](http://ogone.com) integration into your project.
It provides the necessary components to complete a correct payment flow with the [Ogone](http://ogone.com) platform.

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/marlon-be/marlon-ogone/badges/quality-score.png?s=ceabe3e767cad807b589fc63169e6a330d20f9fa)](https://scrutinizer-ci.com/g/marlon-be/marlon-ogone/)
[![Build Status](https://travis-ci.org/marlon-be/marlon-ogone.png)](https://travis-ci.org/marlon-be/marlon-ogone)

Requirements:

- PHP 5.3
- network connection between your webserver and the Ogone platform

As always, this is work in progress. Please feel free to fork this project and let them pull requests coming!

## Overview ##

The library complies to the [PSR-0 standard](http://groups.google.com/group/php-standards/web/psr-0-final-proposal),
so it can be autoloaded using PSR-0 classloaders like the one in Symfony2. See autoload.php for an example.

- Create an EcommercePaymentRequest or CreateAliasRequest, containing all the info needed by Ogone.
- Generate  a form
- Submit it to Ogone (client side)
- Receive a PaymentResponse back from Ogone (as a HTTP Request)

Both EcommercePaymentRequest, CreateAliasRequest and PaymentResponse are authenticated by comparing the SHA sign, which is a hash of the parameters and a secret passphrase. You can create the hash using a ShaComposer.

The library also allows:
- Fetching order information via Ogone API using DirectLinkQueryRequest
- Executing maintenance request via Ogone API using DirectLinkMaintenanceRequest

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

  ![Each parameter followed by the passphrase](http://github.com/marlon-be/marlon-ogone/raw/master/documentation/images/ogone_security_allparameters_sha1_utf8.png)

  Implementation using this library is trivial:

```php
  	<?php
	use Ogone\ShaComposer\AllParametersShaComposer;
	$shaComposer = new AllParametersShaComposer($passphrase);
```

This library currently supports both the legacy method "Main parameters only" and the new method "Each parameter followed by the passphrase". Either can be used with SHA-1 (default), SHA-256 or SHA-512 encryption.

# EcommercePaymentRequest and FormGenerator #

```php
	<?php
	use Ogone\Passphrase;
	use Ogone\Ecommerce\EcommercePaymentRequest;
    use Ogone\ShaComposer\AllParametersShaComposer;
	use Ogone\FormGenerator;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$ecommercePaymentRequest = new EcommercePaymentRequest($shaComposer);

	// Optionally set Ogone uri, defaults to TEST account
	//$ecommercePaymentRequest->setOgoneUri(EcommercePaymentRequest::PRODUCTION);

	// Set various params:
	$ecommercePaymentRequest->setOrderid('123456');
	$ecommercePaymentRequest->setAmount(150); // in cents
	$ecommercePaymentRequest->setCurrency('EUR');
	// ...

	$ecommercePaymentRequest->validate();

	$formGenerator = new SimpleFormGenerator;
	$html = $formGenerator->render($ecommercePaymentRequest);
	// Or use your own generator. Or pass $ecommercePaymentRequest to a view
```

# CreateAliasRequest #

```php
	<?php

	use Ogone\Passphrase;
	use Ogone\DirectLink\CreateAliasRequest;
    use Ogone\ShaComposer\AllParametersShaComposer;
	use Ogone\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$createAliasRequest = new CreateAliasRequest($shaComposer);

	// Optionally set Ogone uri, defaults to TEST account
	// $createAliasRequest->setOgoneUri(CreateAliasRequest::PRODUCTION);

	// set required params
	$createAliasRequest->setPspid('123456');
	$createAliasRequest->setAccepturl('http://example.com/accept');
	$createAliasRequest->setExceptionurl('http://example.com/exception');

	// set optional alias, if empty, Ogone creates one
	$alias = new Alias('customer_123');
	$createAliasRequest->setAlias($alias);

	$createAliasRequest->validate();

	// Now pass $createAliasRequest to a view to build a custom form, you have access to
	// $createAliasRequest->getOgoneUri(), $createAliasRequest->getParameters() and $createAliasRequest->getShaSign()
	// Be sure to add the required fields CN (Card holder's name), CARDNO (Card/account number), ED (Expiry date (MMYY)), CVC (Card Verification Code)
	// and the SHASIGN
```

# DirectLinkPaymentRequest #

```php
	<?php

	use Ogone\DirectLink\DirectLinkPaymentRequest;
	use Ogone\Passphrase;
	use Ogone\ShaComposer\AllParametersShaComposer;
	use Ogone\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$directLinkRequest = new DirectLinkPaymentRequest($shaComposer);
	$directLinkRequest->setOrderid('order_1234');

	$alias = new Alias('customer_123');
	$directLinkRequest->setAlias($alias);
	$directLinkRequest->setPspid('123456');
	$directLinkRequest->setUserId('ogone-api-user');
	$directLinkRequest->setPassword('ogone-api-password');
	$directLinkRequest->setAmount(100);
	$directLinkRequest->setCurrency('EUR');
	$directLinkRequest->validate();

	// now create a url to be posted to Ogone
	// you have access to $directLinkRequest->toArray(), $directLinkRequest->getOgoneUri() and directLinkRequest->getShaSign()
```

# DirectLinkQueryRequest #

```php
	<?php

	use Ogone\DirectLink\DirectLinkQueryRequest;
	use Ogone\Passphrase;
	use Ogone\ShaComposer\AllParametersShaComposer;
	use Ogone\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$directLinkRequest = new DirectLinkQueryRequest($shaComposer);
	$directLinkRequest->setPspid('123456');
	$directLinkRequest->setUserId('ogone-api-user');
	$directLinkRequest->setPassword('ogone-api-password');
	$directLinkRequest->setPayId('order_1234');
	$directLinkRequest->validate();

	// now create a url to be posted to Ogone
	// you have access to $directLinkRequest->toArray(), $directLinkRequest->getOgoneUri() and directLinkRequest->getShaSign()
```

# DirectLinkMaintenanceRequest #

```php
	<?php

	use Ogone\DirectLink\DirectLinkMaintenanceRequest;
	use Ogone\Passphrase;
	use Ogone\ShaComposer\AllParametersShaComposer;
	use Ogone\DirectLink\Alias;

	$passphrase = new Passphrase('my-sha-in-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaInParameterFilter); //optional

	$directLinkRequest = new DirectLinkMaintenanceRequest($shaComposer);
	$directLinkRequest->setPspid('123456');
	$directLinkRequest->setUserId('ogone-api-user');
	$directLinkRequest->setPassword('ogone-api-password');
	$directLinkRequest->setPayId('order_1234');
	$directLinkRequest->setOperation(DirectLinkMaintenanceRequest::OPERATION_AUTHORISATION_RENEW);
	$directLinkRequest->validate();

	// now create a url to be posted to Ogone
	// you have access to $directLinkRequest->toArray(), $directLinkRequest->getOgoneUri() and directLinkRequest->getShaSign()
```

# EcommercePaymentResponse #

```php
  	<?php

	use Ogone\Ecommerce\EcommercePaymentResponse;
	use Ogone\ShaComposer\AllParametersShaComposer;

	// ...

	$ecommercePaymentResponse = new EcommercePaymentResponse($_REQUEST);

	$passphrase = new Passphrase('my-sha-out-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaOutParameterFilter); //optional

	if($ecommercePaymentResponse->isValid($shaComposer) && $ecommercePaymentResponse->isSuccessful()) {
		// handle payment confirmation
	}
	else {
		// perform logic when the validation fails
	}
```

# CreateAliasResponse #

```php
  	<?php

	use Ogone\DirectLink\CreateAliasResponse;
	use Ogone\ShaComposer\AllParametersShaComposer;

	// ...

	$createAliasResponse = new CreateAliasResponse($_REQUEST);

	$passphrase = new Passphrase('my-sha-out-passphrase-defined-in-ogone-interface');
	$shaComposer = new AllParametersShaComposer($passphrase);
	$shaComposer->addParameterFilter(new ShaOutParameterFilter); //optional

	if($createAliasResponse->isValid($shaComposer) && $createAliasResponse->isSuccessful()) {
		// Alias creation is succesful, get the Alias object
		$alias = $createAliasResponse->getAlias();
	}
	else {
		// validation failed, retry?
	}
```

# DirectLinkPaymentResponse #

As the DirectLink payment gets an instant feedback from the server (and no async response) we don't use the SHA validation.

```php
	<?php

	use Ogone\DirectLink\DirectLinkPaymentResponse;

	$directLinkResponse = new DirectLinkPaymentResponse('ogone-direct-link-result-as-xml');

	if($directLinkResponse->isSuccessful()) {
    	// handle payment confirmation
	} else {
    	// perform logic when the validation fails
	}
```



# Parameter filters #
ParameterFilters are used to filter the provided parameters (no shit Sherlock).
Both ShaIn- and ShaOutParameterFilters are provided and are based on the parameter lists defined in the Ogone documentation.
Parameter filtering is optional, but we recommend using them to enforce expected parameters.
