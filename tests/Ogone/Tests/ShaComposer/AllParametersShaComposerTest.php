<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\Tests\ShaComposer;

use Ogone\HashAlgorithm;
use Ogone\ParameterFilter\ShaOutParameterFilter;
use Ogone\Passphrase;
use Ogone\ShaComposer\AllParametersShaComposer;

class AllParametersShaComposerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideSha1Request
     */
    public function Sha1StringIsComposedCorrectly(Passphrase $passphrase, array $request, $expectedSha)
    {
        $composer = new AllParametersShaComposer($passphrase, new HashAlgorithm(HashAlgorithm::HASH_SHA1));
        $composer->addParameterFilter(new ShaOutParameterFilter);
        $this->assertEquals($expectedSha, $composer->compose($request));
    }

    /**
     * @test
     * @dataProvider provideSha256Request
     */
    public function Sha256StringIsComposedCorrectly(Passphrase $passphrase, array $request, $expectedSha)
    {
        $composer = new AllParametersShaComposer($passphrase, new HashAlgorithm(HashAlgorithm::HASH_SHA256));
        $composer->addParameterFilter(new ShaOutParameterFilter);
        $this->assertEquals($expectedSha, $composer->compose($request));
    }

    /**
     * @test
     * @dataProvider provideSha512Request
     */
    public function Sha512StringIsComposedCorrectly(Passphrase $passphrase, array $request, $expectedSha)
    {
        $composer = new AllParametersShaComposer($passphrase, new HashAlgorithm(HashAlgorithm::HASH_SHA512));
        $composer->addParameterFilter(new ShaOutParameterFilter);
        $this->assertEquals($expectedSha, $composer->compose($request));
    }

    public function provideSha1Request()
    {
        $passphrase = new Passphrase('Mysecretsig1875!?');

        $expectedSha1 = 'B209960D5703DD1047F95A0F97655FFE5AC8BD52';
        $request1 = $this->createMinimalParameterSet();

        $expectedSha2 = 'D58400479DCEDD6B6C7E67D61FDC0CC9E6ED65CB';
        $request2 = $this->createExtensiveParameterSet();



        return array(
            array($passphrase, $request1, $expectedSha1),
            array($passphrase, $request2, $expectedSha2),
        );
    }

    public function provideSha256Request()
    {
        $passphrase = new Passphrase('Mysecretsig1875!?');

        $expectedSha1 = 'FD15F9371F2B42E3CAEC53BF2576AC89AAEBF53FD8FBA8F0B2EA13EAA823189D';
        $request1 = $this->createMinimalParameterSet();

        $expectedSha2 = 'A06D4534724885350BA5350731B02F4083370F8C9EED59D1F1C5E2B78EC3C257';
        $request2 = $this->createExtensiveParameterSet();

        return array(
            array($passphrase, $request1, $expectedSha1),
            array($passphrase, $request2, $expectedSha2),
        );
    }

    public function provideSha512Request()
    {
        $passphrase = new Passphrase('Mysecretsig1875!?');

        $expectedSha1 = '5377F95D498947BECC23E02C2C7DDE182EE1221F1A6629B091110DF653FE0C32FCACF5F9B87B4C5168FC12B7183095623750004355DE938A2B8DECC6DB6D9F62';
        $request1 = $this->createMinimalParameterSet();

        $expectedSha2 = '31B74E4E0C7BCE4DED9DEAA97D4D3FB419EF6E2FDBD98C18D340B276A9F751E747972A0469A74B73E4C41F38F0F3F58BAD8D7107CA54DF936569852887EB6BE4';
        $request2 = $this->createExtensiveParameterSet();

        return array(
            array($passphrase, $request1, $expectedSha1),
            array($passphrase, $request2, $expectedSha2),
        );
    }

    protected function createMinimalParameterSet()
    {
        return array(
            'currency' => 'EUR',
            'ACCEPTANCE' => 1234,
            'amount' => 15,
            'BRAND' => 'VISA',
            'CARDNO' => 'xxxxxxxxxxxx1111',
            'NCERROR' => 0,
            'PAYID' => 32100123,
            'PM' => 'CreditCard',
            'STATUS' => 9,
            'orderID' => 12,
            'unknownparam' => 'some value',
        );
    }

    protected function createExtensiveParameterSet()
    {
        return array (
            'orderID' => 'myorderid1678834094',
            'currency' => 'EUR',
            'amount' => '99',
            'PM' => 'CreditCard',
            'ACCEPTANCE' => 'test123',
            'STATUS' => '9',
            'CARDNO' => 'XXXXXXXXXXXX1111',
            'ED' => '0312',
            'CN' => 'Some Name',
            'TRXDATE' => '01/10/11',
            'PAYID' => '9126297',
            'NCERROR' => '0',
            'BRAND' => 'VISA',
            'COMPLUS' => 'my feedbackmessage',
            'IP' => '12.123.12.123',
            'foo' => 'bar',
        );
    }
}
