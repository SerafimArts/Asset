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
use lessc as LessCompiler;
use Serafim\Asset\Driver\AbstractDriver;

class LessPhpDriver extends AbstractDriver
{
    protected static $compiler;

    public function compile($sources, $app)
    {
        if (!self::$compiler) {
            self::$compiler = new LessCompiler;
            self::$compiler
                ->addImportDir(dirname($this->file->getRealPath()));
        }
        // disable cache (less has imports)
        return self::$compiler->compile($sources, $this->file->getFilename());
    }

    public function getOutputExtension()
    {
        return 'css';
    }
}
