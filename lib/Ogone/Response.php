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

interface Response {

    /** @var string */
    const SHASIGN_FIELD = 'SHASIGN';

    public function isSuccessful();
}
