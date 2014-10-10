<?php namespace Asset;

/**
 * This file is part of Asset package.
 *
 * serafim <nesk@xakep.ru> (03.06.2014 14:13)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Exception;

/**
 * Class Parser
 * @package Asset
 */
class Parser
{
    /**
     * @var array
     */
    protected $drivers = [
        '\Asset\Driver\CssDriver',
        '\Asset\Driver\ScssDriver',
        '\Asset\Driver\CoffeeDriver',
        '\Asset\Driver\LessDriver',
        '\Asset\Driver\JsDriver',
    ];

    /**
     * @var string
     */
    private $_fullPath = '';

    /**
     * @var string
     */
    private $_extension = '';

    /**
     * @var string
     */
    private $_sources = '';

    /**
     * @var Driver
     */
    private $_driver;

    /**
     * @var Config
     */
    private $_config;


    /**
     * @param $path
     * @param Config $config
     */
    public function __construct($path, Config $config)
    {
        $this->_fullPath = $path;
        $this->_config = $config;
        $this->_extension = pathinfo($path, PATHINFO_EXTENSION);
        $this->_sources = file_get_contents($path);

        $this->_driver = $this->getDriver();
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function getDriver()
    {
        foreach ($this->drivers as $driver) {
            if (in_array($this->_extension, $driver::getExtensions())) {
                return new $driver($this, $this->_config);
            }
        }
        throw new Exception('Can not find driver for ' . $this->_extension);
    }

    /**
     * @param bool $recursive
     * @return $this
     */
    public function parse($recursive = false)
    {
        $matches = [];
        $patterns = $this->_driver->getPatterns();

        foreach ($patterns as $p) {
            $pattern = '/' . $p . '\s/u';
            preg_match_all($pattern, $this->_sources, $m);
            for ($i = 0, $count = count($m[0]); $i < $count; $i++) {
                $matches[$m[0][$i]] = trim($m[1][$i]);
            }
        }

        foreach ($matches as $string => $value) {
            $path = dirname($this->_fullPath) . '/' . $value;
            #print_r($path);
            $parser = new self($path, $this->_config);

            if ($recursive) {
                $parser->parse($recursive);
            }

            $this->_sources = str_replace(
                $string,
                $parser->compile()->getSources(),
                $this->_sources
            );
        }

        return $this;
    }

    /**
     * @return string
     */
    public function compile()
    {
        return $this->_driver->getSerializer();
    }

    /**
     * @return string
     */
    public function getSources()
    {
        return $this->_sources;
    }


    /**
     * @return string
     */
    public function getPath()
    {
        return $this->_fullPath;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->compile();
    }
}
