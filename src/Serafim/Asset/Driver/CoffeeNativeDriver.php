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
use Serafim\Asset\Serialize\JsSerialize;
use Symfony\Component\Finder\SplFileInfo;
use CoffeeScript\Compiler as CSCompiler;

/**
 * Class CoffeeNativeDriver
 * @package Serafim\Asset\Driver
 */
class CoffeeNativeDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @var array
     */
    protected static $patterns = [
        '#\s*=\s*require\s+{file}'
    ];

    /**
     * @return bool|void
     * @throws NoPackageException
     */
    public static function check()
    {
        if (!class_exists('\\CoffeeScript\\Compiler')) {
            throw new NoPackageException('Can not build CoffeeScript files. ' .
                'Please install "coffeescript/coffeescript" package.');
        }
    }

    /**
     * @param SplFileInfo $file
     * @return string
     * @throws \CoffeeScript\Error
     */
    public function compile(SplFileInfo $file)
    {
        dd($file->getRealPath());
        $content = $file->getContents();
        return CSCompiler::compile($content, [
            'filename' => $file->getRelativePathname(),
            'header' => false
        ]);
    }

    /**
     * @param SplFileInfo $file
     * @return JsSerialize|void
     */
    public static function getSerializationInterface(SplFileInfo $file)
    {
        return new JsSerialize($file);
    }
}
