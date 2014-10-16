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
 * Interface DriverInterface
 * @package Serafim\Asset\Driver
 */
interface DriverInterface
{
    /**
     * @param SplFileInfo $file
     * @return mixed
     */
    public function compile(SplFileInfo $file, $content = null);

    /**
     * @return mixed
     */
    public static function getManifestPatterns();

    /**
     * @return mixed
     */
    public static function parseManifest();

    /**
     * @return mixed
     */
    public static function check();

    /**
     * @param SplFileInfo $file
     * @return mixed
     */
    public static function getSerializationInterface(SplFileInfo $file);
}
