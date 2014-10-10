<?php namespace Asset\Driver;

/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 14:03)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * Class CssDriver
 * @package Asset\Driver
 */
class CssDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @var string
     */
    protected static $extensions = ['css'];
    /**
     * @var string
     */
    protected $type = self::TYPE_STYLE;
    /**
     * @var string
     */
    protected $patterns = [
        '\/\*\s*=\s*require\s+{file}\s*\*\/',
        '@import\s+"{file}";'
    ];

    /**
     * @return mixed
     */
    public function parse()
    {
        return $this->getSources();
    }
}