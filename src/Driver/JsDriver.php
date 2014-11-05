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

use Serafim\Asset\Compiler\File;
use Serafim\Asset\Serialize\JsSerialize;
use SplFileInfo;

class JsDriver extends AbstractDriver
{
    public static function getSerializationInterface(File $file)
    {
        return new JsSerialize($file);
    }
}
