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


/**
 * Class Events
 * @package Serafim\Asset
 */
class Events
{
    // Boot event
    const BOOT      = 'asset.boot';

    // File read event
    const READ      = 'asset.read';

    // File compile event
    const COMPILE   = 'asset.compile';

    // Publish file event
    const PUBLISH   = 'asset.publish';
}
