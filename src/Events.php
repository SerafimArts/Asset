<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Serafim\Asset;

class Events
{
    const BOOT      = 'asset.boot';
    const READ      = 'asset.read';
    const COMPILE   = 'asset.compile';
    const PUBLISH   = 'asset.publish';
}