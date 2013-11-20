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
use Ogone\Passphrase;
use Ogone\Alias;
use Ogone\CreateAliasRequest;
use Ogone\CreateAliasResponse;
use Ogone\ShaComposer\AllParametersShaComposer;
use Ogone\ParameterFilter\ShaOutParameterFilter;
use Ogone\DirectLinkPaymentRequest;
use Ogone\DirectLinkPaymentResponse;

class OgoneTest extends \TestCase {

    /**
     * @test
     */
    public function AliasCreationIsSuccessful()
    {
        $passphraseOut = new Passphrase($GLOBALS['passphrase_sha_out']);
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
        $passphrase = new Passphrase($GLOBALS['passphrase_sha_in']);
        $shaComposer = new AllParametersShaComposer($passphrase);
        $directLinkRequest = new DirectLinkPaymentRequest($shaComposer);

        $orderId = uniqid('order_'); // create a unique order id
        $directLinkRequest->setOrderid($orderId);

        $alias = new Alias($alias);
        $directLinkRequest->setPspid($GLOBALS['pspid']);
        $directLinkRequest->setUserId($GLOBALS['userid']);
        $directLinkRequest->setPassword($GLOBALS['password']);
        $directLinkRequest->setAlias($alias);
        $directLinkRequest->setAmount(100);
        $directLinkRequest->setCurrency('EUR');
        $directLinkRequest->validate();

        $body = array();
        foreach($directLinkRequest->toArray() as $key => $value) {
            $body[strtoupper($key)] = $value;
        }

        $body['SHASIGN'] = $directLinkRequest->getShaSign();

        $client = new Client($directLinkRequest->getOgoneUri());
        $request = $client->post(null, null, $body);
        $response = $request->send();

        $directLinkResponse = new DirectLinkPaymentResponse($response->getBody(true));

        $this->assertTrue($directLinkResponse->isSuccessful());
    }

    /**
     * @test
     */
    public function AliasIsCreatedByOgone()
    {
        $passphraseOut = new Passphrase($GLOBALS['passphrase_sha_out']);
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
        $passphraseOut = new Passphrase($GLOBALS['passphrase_sha_out']);
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
        $passphrase = new Passphrase($GLOBALS['passphrase_sha_in']);
        $shaComposer = new AllParametersShaComposer($passphrase);

        $createAliasRequest = new CreateAliasRequest($shaComposer);
        $createAliasRequest->setPspid($GLOBALS['pspid']);
        $createAliasRequest->setAccepturl('http://www.example.com');
        $createAliasRequest->setExceptionurl('http://www.example.com');

        if($createAlias == true) {
            $unique_alias = uniqid('customer_'); // create a unique alias
            $alias = new Alias($unique_alias);
            $createAliasRequest->setAlias($alias);
        }

        $createAliasRequest->validate();

        $body = array();
        foreach($createAliasRequest->toArray() as $key => $value) {
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