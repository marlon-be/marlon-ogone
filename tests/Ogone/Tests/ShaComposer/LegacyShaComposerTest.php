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

class LegacyShaComposerTest extends \PHPUnit_Framework_TestCase
{
    const PASSPHRASE = 'passphrase-set-in-ogone-interface';
    const SHA1STRING = '66BF34D8B3EF2136E0C267BDBC1F708B8D75A8AA';
    const SHA256STRING = '882D85FCCC6112A33D3B8A571C11723CAA6B642EED70843B35B15ABA0C2AD637';
    const SHA512STRING = '8552200DD108CB5633A27D6D0A1FAB54378CB2385BFCEB27487992D16F5A7565E5DD4D38C0F2DB294213CD02E434F311021749E6DAB187357F786E3F199781CA';

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
