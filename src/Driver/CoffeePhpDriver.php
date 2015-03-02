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

use CoffeeScript\Compiler;
use SplFileInfo;
use Serafim\Asset\Driver\AbstractDriver;

class CoffeePhpDriver extends JsDriver
{
    public function compile($sources, $app)
    {
        return $this->cache($app, function() use ($sources) {
            return Compiler::compile($sources, [
                'filename' => $this->file->getFilename(),
                'header' => false
            ]);
        });
    }

    public function getOutputExtension()
    {
        return 'js';
    }
}
