<?php

/*
 * This file is part of the Marlon Ogone package.
 *
 * (c) Marlon BVBA <info@marlon.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ogone\ParameterFilter;

class AliasShaInParameterFilter implements ParameterFilter
{
    private $allowed = array(
        'ACCEPTURL', 'ALIAS', 'ALIASPERSISTEDAFTERUSE', 'BRAND', 'EXCEPTIONURL',
        'LANGUAGE', 'ORDERID', 'PARAMPLUS', 'PSPID'
    );

    public function filter(array $parameters)
    {
        $parameters = array_change_key_case($parameters, CASE_UPPER);
        return array_intersect_key($parameters, array_flip($this->allowed));
    }
}
