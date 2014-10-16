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
 * Class AbstractDriver
 * @package Serafim\Asset\Driver
 */
abstract class AbstractDriver
{
    /**
     * @var bool
     */
    protected static $parseManifest = true;

    /**
     * @var array
     */
    protected static $patterns = [
        '\/\/\s*=\s*require\s+{file}'
    ];

    /**
     * @param SplFileInfo $file
     * @return mixed|string
     */
    public function compile(SplFileInfo $file, $content = null)
    {
        if ($content === null) {
            return $file->getContents();
        }
        return $content;
    }

    /**
     * @param string $file
     * @return array
     */
    public static function getManifestPatterns($file = '.*?')
    {
        $result = [];
        foreach (self::$patterns as $pattern) {
            $result[] = str_replace('{file}', '(' . $file . ')', $pattern);
        }
        return $result;
    }

    /**
     * @return bool
     */
    public static function parseManifest()
    {
        return self::$parseManifest;
    }

    /**
     * @return bool
     */
    public static function check()
    {
        return true;
    }

    /**
     * @param SplFileInfo $file
     */
    public static function getSerializationInterface(SplFileInfo $file)
    {

    }

}
