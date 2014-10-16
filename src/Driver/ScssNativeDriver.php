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

use Serafim\Asset\Exception\NoPackageException;
use Serafim\Asset\Serialize\CssSerialize;
use Symfony\Component\Finder\SplFileInfo;
use Leafo\ScssPhp\Compiler as ScssCompiler;

/**
 * Class ScssNativeDriver
 * @package Serafim\Asset\Driver
 */
class ScssNativeDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @return bool|void
     * @throws NoPackageException
     */
    public static function check()
    {
        if (!class_exists('\\Leafo\\ScssPhp\\Compiler')) {
            throw new NoPackageException('Can not build Scss files. ' .
                'Please install "leafo/scssphp" package.');
        }
    }

    /**
     * @param SplFileInfo $file
     * @return string
     * @throws \CoffeeScript\Error
     */
    public function compile(SplFileInfo $file)
    {
        $content = $file->getContents();
        $scss = new ScssCompiler;
        $scss->setImportPaths([dirname($file->getRealPath())]);
        return $scss->compile($content, $file->getFilename());
    }

    /**
     * @param SplFileInfo $file
     * @return CssSerialize|void
     */
    public static function getSerializationInterface(SplFileInfo $file)
    {
        return new CssSerialize($file);
    }
}
