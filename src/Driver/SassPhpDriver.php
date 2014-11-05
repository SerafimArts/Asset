<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (05.11.2014 13:35)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 
namespace Serafim\Asset\Driver;

use SplFileInfo;
use SassParser;
use Serafim\Asset\Driver\AbstractDriver;

class SassPhpDriver extends AbstractDriver
{
    protected static $compiler;

    public function compile($sources, $cache)
    {
        if (!self::$compiler) {
            self::$compiler = new SassParser([
                'syntax' => 'sass',
                'cache' => false,
                'style' => 'nested',
                'debug' => false
            ]);
        }
        // disable cache (sass has imports)
        return self::$compiler->toCss($sources, false);
    }

    public function getOutputExtension()
    {
        return 'css';
    }
}
