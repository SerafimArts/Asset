<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Driver;

use Serafim\Asset\Serialize\JsSerialize;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class JsDriver
 * @package Serafim\Asset\Driver
 */
class JsDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @param SplFileInfo $file
     * @return mixed|JsSerialize|void
     */
    public static function getSerializationInterface(SplFileInfo $file)
    {
        return new JsSerialize($file);
    }
}
