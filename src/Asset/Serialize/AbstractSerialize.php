<?php namespace Asset\Serialize;

/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 14:27)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App;
use Asset\Config;
use Asset\Driver\AbstractDriver;
use Asset\Driver\DriverInterface as Driver;
use CssMin;
use JSMin;

/**
 * Class AbstractSerialize
 * @package Asset\Serialize
 */
abstract class AbstractSerialize
{
    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @var string
     */
    protected $result = '';

    /**
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
        $this->result = $driver->compile();
    }

    /**
     * @param array $args
     * @return mixed
     */
    abstract function getInline($args = []);

    /**
     * @return string
     */
    public function getSourcesPath()
    {
        $conf = $this->driver->getConfig();
        $file = md5($this->result) . '.' . $this->getPublicExtension();
        $cache = $conf->get(Config::C_PUBLIC_PATH, '/' . $file);

        if (!is_dir($path = $conf->get(Config::C_PUBLIC_PATH))) {
            mkdir($path, 0777, true);
        }

        if (!file_exists($cache)) {
            file_put_contents($cache, $this->getSources());
        }

        return $conf->get(Config::C_PUBLIC_HTTP, '/' . $file);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getPublicExtension()
    {
        switch ($this->driver->getType()) {
            case AbstractDriver::TYPE_STYLE:
                return 'css';
            case AbstractDriver::TYPE_SCRIPT:
                return 'js';
        }

        throw new \Exception('Can not find serializer driver for type ' . $this->driver->getType());
    }

    /**
     *
     */
    public function getSources()
    {
        $result = $this->result;
        if ($m = $this->driver->getConfig()->get(Config::C_MINIFY)) {
            $result = $this->minify($result);
        }
        return $result;
    }

    /**
     * @param $sources
     * @return string
     * @throws \Exception
     */
    protected function minify($sources)
    {
        switch ($this->driver->getType()) {
            case AbstractDriver::TYPE_STYLE:
                return CssMin::minify($sources);
            case AbstractDriver::TYPE_SCRIPT:
                return JSMin::minify($sources);
        }

        throw new \Exception('Can not minify ' . $this->driver->getType());
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getLink();
    }

    /**
     * @param array $args
     * @return mixed
     */
    abstract function getLink($args = []);

    /**
     * @param $name
     * @param null $content
     * @param array $args
     * @return string
     */
    protected function tag($name, $content = null, $args = [])
    {
        if ($content === null) {
            return '<' . $name . ' ' . $this->parseArgs($args) . ' />';
        }
        return '<' . $name . ' ' . $this->parseArgs($args) . '>' . $content . '</' . $name . '>';
    }

    /**
     * @param $args
     * @return string
     */
    protected function parseArgs($args)
    {
        $result = [];
        foreach ($args as $attr => $val) {
            $result[] = $attr . '="' . $val . '"';
        }
        return implode(' ', $result);
    }
}
