<?php namespace Asset\Driver;

/**
 * This file is part of Asset package.
 *
 * serafim <nesk@xakep.ru> (03.06.2014 14:03)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use CoffeeScript\Compiler as CoffeeScriptCompiler;


/**
 * Class CssDriver
 * @package Asset\Driver
 */
class CoffeeDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @var string
     */
    protected static $extensions = ['coffee'];

    /**
     * @var string
     */
    protected $type = self::TYPE_SCRIPT;

    /**
     * @var string
     */
    protected $patterns = [
        '#\s*=\s*require\s+{file}',
        'require\s+{file};?',
        'require\s*\(\s*{file}\);?'
    ];

    /**
     * @return mixed
     */
    public function parse()
    {
        return CoffeeScriptCompiler::compile(
            $this->getSources(),
            [
                'filename' => $this->getPath(),
                'header' => false
            ]
        );
    }
}
