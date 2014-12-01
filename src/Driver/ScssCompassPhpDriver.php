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
use Leafo\ScssPhp\Compiler;
use Serafim\Asset\Driver\AbstractDriver;
use scss_compass as Compass;

class ScssCompassPhpDriver extends AbstractDriver
{
    protected static $compiler;

    public function compile($sources, $app)
    {
        if (!self::$compiler) {
            self::$compiler = new Compiler();
            $compass = new Compass(self::$compiler);
            self::$compiler
                ->addImportPath(dirname($this->file->getRealPath()));
        }
        // disable cache (scss has imports)
        return self::$compiler->compile($sources, $this->file->getFilename());
    }

    public function getOutputExtension()
    {
        return 'css';
    }
}
