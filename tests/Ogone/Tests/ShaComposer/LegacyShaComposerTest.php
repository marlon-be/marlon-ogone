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
use Ogone\Passphrase;
use Ogone\PaymentResponse;
use Ogone\ShaComposer\LegacyShaComposer;

class LegacyShaComposerTest extends \TestCase
{
    const PASSPHRASE = 'passphrase-set-in-ogone-interface';
    const SHA1STRING = '95F4329A49796406B62D681B4E128BCA9A92347E';
    const SHA256STRING = '3E38090DF2C1214B84755E1F6A8CAB321010A59C9F79CDECA2AD8D055DCF28BE';
    const SHA512STRING = 'F22A1EF9C432CFC440511FC9617792109241CA8DD19FB14414B81C5519572CBE474BFE568C5847FF6404B26077F716AD41332AA6A3CFE2A2A73F33015B291CA3';

    /** @test */
    public function defaultParameters()
    {
        $aRequest = $this->provideRequest();

        $composer = new LegacyShaComposer(new Passphrase(self::PASSPHRASE));
        $shaString = $composer->compose($aRequest);

        $this->assertEquals(self::SHA1STRING, $shaString);
    }

    /** @test */
    public function Sha1StringCanBeComposed()
    {
        $aRequest = $this->provideRequest();

        $composer = new LegacyShaComposer(new Passphrase(self::PASSPHRASE), new HashAlgorithm(HashAlgorithm::HASH_SHA1));
        $shaString = $composer->compose($aRequest);

        $this->assertEquals(self::SHA1STRING, $shaString);
    }

    /** @test */
    public function Sha256StringCanBeComposed()
    {
        $aRequest = $this->provideRequest();

        $composer = new LegacyShaComposer(new Passphrase(self::PASSPHRASE), new HashAlgorithm(HashAlgorithm::HASH_SHA256));
        $shaString = $composer->compose($aRequest);

        $this->assertEquals(self::SHA256STRING, $shaString);
    }

    /** @test */
    public function Sha512StringCanBeComposed()
    {
        $aRequest = $this->provideRequest();

        $composer = new LegacyShaComposer(new Passphrase(self::PASSPHRASE), new HashAlgorithm(HashAlgorithm::HASH_SHA512));
        $shaString = $composer->compose($aRequest);

        $this->assertEquals(self::SHA512STRING, $shaString);
    }

    private function provideRequest()
    {
        return array(
            'ACCEPTANCE' => 'test123',
            'AMOUNT' => '19.08',
            'BRAND' => 'VISA',
            'CARDNO' => 'XXXXXXXXXXXX1111',
            'CN' => 'Marlon',
            'CURRENCY' => 'EUR',
            'ED' => '0113',
            'IP' => '81.82.214.142',
            'NCERROR' => 0,
            'ORDERID' => 2101947639,
            'PAYID' => 10673859,
            'PM' => 'CreditCard',
            'STATUS' => PaymentResponse::STATUS_AUTHORISED,
            'TRXDATE' => '07/05/11'
        );
    }
}
