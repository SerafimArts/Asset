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

use Leafo\ScssPhp\Compiler;

/**
 * Class ScssPhpDriver
 * @package Serafim\Asset\Driver
 */
class ScssPhpDriver extends CssDriver
{
    /**
     * @var
     */
    protected static $compiler;

    /**
     * @param $sources
     * @param $app
     * @return mixed|string
     */
    public function compile($sources, $app)
    {
        if (!self::$compiler) {
            self::$compiler = new Compiler();
            self::$compiler
                ->addImportPath(dirname($this->file->getRealPath()));
        }

        // disable cache (scss has imports)
        return self::$compiler->compile($sources, $this->file->getFilename());
    }

    /**
     * @return string
     */
    public function getOutputExtension()
    {
        return 'css';
    }
}
