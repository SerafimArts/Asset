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

use SplFileInfo;

abstract class AbstractDriver
{
    public function compile(SplFileInfo $file)
    {
        return file_get_contents($file->getRealPath());
    }

    public static function check()
    {
        return true;
    }
}
