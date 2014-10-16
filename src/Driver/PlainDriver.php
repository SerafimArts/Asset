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

use Symfony\Component\Finder\SplFileInfo;

/**
 * Class PlainDriver
 * @package Serafim\Asset\Driver
 */
class PlainDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @param SplFileInfo $file
     * @return mixed|string
     */
    public function compile(SplFileInfo $file)
    {
        return $file->getContents();
    }
}
