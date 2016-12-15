<?php
/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests;

use Guzzle\Http\Client;
use Ogone\DirectLink\Eci;
use Ogone\DirectLink\PaymentOperation;
use Ogone\Passphrase;
use Ogone\DirectLink\Alias;
use Ogone\DirectLink\CreateAliasRequest;
use Ogone\DirectLink\CreateAliasResponse;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\ParameterFilter\ShaOutParameterFilter;
use Ogone\DirectLink\DirectLinkPaymentRequest;
use Ogone\DirectLink\DirectLinkPaymentResponse;

/**
 * @group integration
 */
class OgoneTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function AliasCreationIsSuccessful()
    {
        $passphraseOut = new Passphrase(PASSPHRASE_SHA_OUT);
        $shaOutComposer = new AllParametersShaComposer($passphraseOut);
        $shaOutComposer->addParameterFilter(new ShaOutParameterFilter());

        $createAliasResponse = $this->provideAliasResponse();

        $this->assertTrue($createAliasResponse->isValid($shaOutComposer));
        $this->assertTrue($createAliasResponse->isSuccessful());

        return (string) $createAliasResponse->getAlias();
    }

    /**
     * @test
     * @depends AliasCreationIsSuccessful
     */
    public function DirectLinkPaymentIsSuccessful($alias)
    {
        $passphrase = new Passphrase(PASSPHRASE_SHA_IN);
        $shaComposer = new AllParametersShaComposer($passphrase);
        $directLinkRequest = new DirectLinkPaymentRequest($shaComposer);

        $orderId = uniqid('order_'); // create a unique order id
        $directLinkRequest->setOrderid($orderId);

        $alias = new Alias($alias);
        $directLinkRequest->setPspid(PSPID);
        $directLinkRequest->setUserId(USERID);
        $directLinkRequest->setPassword(PASSWORD);
        $directLinkRequest->setAlias($alias);
        $directLinkRequest->setAmount(100);
        $directLinkRequest->setCurrency('EUR');
        $directLinkRequest->setEci(new Eci(Eci::ECOMMERCE_RECURRING));
        $directLinkRequest->setOperation(new PaymentOperation(PaymentOperation::REQUEST_FOR_DIRECT_SALE));
        $directLinkRequest->validate();

        $body = array();
        foreach ($directLinkRequest->toArray() as $key => $value) {
            $body[strtoupper($key)] = $value;
        }

        $body['SHASIGN'] = $directLinkRequest->getShaSign();

        $client = new Client($directLinkRequest->getOgoneUri());
        $request = $client->post(null, null, $body);
        $response = $request->send();

        $directLinkResponse = new DirectLinkPaymentResponse($response->getBody(true));

        $this->assertTrue($directLinkResponse->isSuccessful());

        return $alias;
    }

    /**
     * @test
     */
    public function AliasIsCreatedByOgone()
    {
        $passphraseOut = new Passphrase(PASSPHRASE_SHA_OUT);
        $shaOutComposer = new AllParametersShaComposer($passphraseOut);
        $shaOutComposer->addParameterFilter(new ShaOutParameterFilter());

        $createAliasResponse = $this->provideAliasResponse(false);

        $this->assertTrue($createAliasResponse->isValid($shaOutComposer));
        $this->assertTrue($createAliasResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function CreateAliasInvalid()
    {
        $passphraseOut = new Passphrase(PASSPHRASE_SHA_OUT);
        $shaOutComposer = new AllParametersShaComposer($passphraseOut);
        $shaOutComposer->addParameterFilter(new ShaOutParameterFilter());

        $createAliasResponse = $this->provideAliasResponse(true, true);

        $this->assertTrue($createAliasResponse->isValid($shaOutComposer));
        $this->assertFalse($createAliasResponse->isSuccessful());
    }


    public function provideAliasResponse($createAlias = true, $noValidCardnumber = false)
    {
        /*
         *  Create an alias request to Ogone
         */
        $passphrase = new Passphrase(PASSPHRASE_SHA_IN);
        $shaComposer = new AllParametersShaComposer($passphrase);

        $createAliasRequest = new CreateAliasRequest($shaComposer);
        $createAliasRequest->setPspid(PSPID);
        $createAliasRequest->setAccepturl('http://www.example.com');
        $createAliasRequest->setExceptionurl('http://www.example.com');

        if ($createAlias == true) {
            $unique_alias = uniqid('customer_'); // create a unique alias
            $alias = new Alias($unique_alias);
            $createAliasRequest->setAlias($alias);
        }

        $createAliasRequest->validate();

        $body = array();
        foreach ($createAliasRequest->toArray() as $key => $value) {
            $body[strtoupper($key)] = $value;
        }

        $body['SHASIGN'] = $createAliasRequest->getShaSign();
        $body['CN'] = 'Don Corleone';
        $body['CARDNO'] = ($noValidCardnumber) ? '' : '4111111111111111'; // Ogone Visa test cardnumber
        $body['CVC'] = '777';
        $body['ED'] = date('my', strtotime('+1 year')); // test-date should be in the future

        $client = new Client($createAliasRequest->getOgoneUri());
        $request = $client->post(null, null, $body);
        $response = $request->send();

        $url = parse_url($response->getInfo('url'));
        $params = array();
        parse_str($url['query'], $params);

        /*
         * Validate alias response from Ogone
         */

        $createAliasResponse = new CreateAliasResponse($params);

        return $createAliasResponse;
    }
}
