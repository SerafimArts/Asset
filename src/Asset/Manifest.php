<?php namespace Asset;

/**
 * This file is part of Asset package.
 *
 * serafim <nesk@xakep.ru> (03.06.2014 13:21)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Exception;

/**
 * Class Manifest
 * @package Asset
 */
class Manifest
{
    /**
     * @var Config
     */
    private $_config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->_config = $config;
    }


    /**
     * @param $path
     * @param bool $recursive
     * @return string
     * @throws \Exception
     */
    public function make($path, $recursive = false)
    {
        $full = $this->_config->get(Config::C_Asset_PATH, '/' . $path);

        if (!file_exists($full)) {
            throw new Exception(
                'Can not make asset `' .
                $path . '`. File not found in ' . $full
            );
        }

        $parser = (new Parser(realpath($full), $this->_config))
            ->parse($recursive);

        return $parser->compile();
    }
}
