<?php namespace Asset;

/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 13:24)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App;
use Exception;

/**
 * Class Config
 * @package Asset
 */
class Config
{
    const C_PUBLIC_HTTP = 'public.http';
    const C_PUBLIC_PATH = 'public.path';
    const C_CACHE_PATH  = 'cache.path';
    const C_ASSETS_PATH = 'assets.path';
    const C_MINIFY      = 'minify';

    /**
     * @var array
     */
    private $_config = [
        self::C_PUBLIC_HTTP => '',
        self::C_PUBLIC_PATH => '',
        self::C_CACHE_PATH  => '',
        self::C_ASSETS_PATH => '',
        self::C_MINIFY      => false
    ];

    /**
     * @param array $args
     * @throws \Exception
     */
    public function __construct($args = [])
    {
        foreach ($args as $type => $val) {
            if (isset($this->_config[$type])) {
                $this->_config[$type] = $val;
            } else {
                throw new Exception('Undefined config variable ' . $type);
            }
        }

        if (!isset($args[self::C_MINIFY])) {
            $this->_config[self::C_MINIFY] = App::environment('production');
        }
    }

    /**
     * @param $name
     * @param string $append
     * @return mixed
     * @throws \Exception
     */
    public function get($name, $append = '')
    {
        if (isset($this->_config[$name])) {
            return $this->_config[$name] . $append;
        }
        throw new Exception('Undefined config key ' . $name);
    }
}