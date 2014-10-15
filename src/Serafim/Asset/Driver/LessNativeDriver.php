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
use lessc as LessCompiler;

/**
 * Class LessNativeDriver
 * @package Serafim\Asset\Driver
 */
class LessNativeDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @return bool|void
     * @throws NoPackageException
     */
    public static function check()
    {
        if (!class_exists('\\lessc')) {
            throw new NoPackageException('Can not build Less files. ' .
                'Please install "leafo/lessphp" package.');
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
        $less = new LessCompiler;
        $less->setImportDir([dirname($file->getRealPath())]);
        return $less->compile($content);
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
