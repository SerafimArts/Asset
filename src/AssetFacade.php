<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (14.10.2014 19:43)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Serafim\Asset;

use Illuminate\Support\Facades\Facade;

/**
 * Class Asset
 * @package Serafim\Asset\Laravel
 */
class AssetFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'asset'; }

}
