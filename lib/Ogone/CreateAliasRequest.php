<?php
/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone;

use Ogone\ShaComposer\ShaComposer;

class CreateAliasRequest extends AbstractRequest {

    const TEST = "https://secure.ogone.com/ncol/test/alias_gateway_utf8.asp";
    const PRODUCTION = "https://secure.ogone.com/ncol/prod/alias_gateway_utf8.asp";

    public function __construct(ShaComposer $shaComposer)
    {
        $this->shaComposer = $shaComposer;
        $this->ogoneUri = self::TEST;
    }

    public function getRequiredFields()
    {
        $requiredfields = array(
            'pspid', 'accepturl', 'exceptionurl'
        );

        return $requiredfields;
    }

    public function getValidOgoneUris()
    {
        return array(self::TEST, self::PRODUCTION);
    }

    public function setAlias($alias)
    {
        if(strlen($alias) > 50) {
            throw new InvalidArgumentException("Alias is too long");
        }
        $this->parameters['alias'] = $alias;
    }
} 