<?php namespace Asset\Driver;

/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (03.06.2014 14:00)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use App;
use Asset\Config;
use Asset\Parser;
use Asset\Serialize\ScriptSerialize;
use Asset\Serialize\StyleSerialize;
use Exception;

/**
 * Class AbstractDriver
 * @package Asset\Driver
 */
abstract class AbstractDriver
{
    const TYPE_SCRIPT = 'script';
    const TYPE_STYLE = 'style';
    const TYPE_UNDEFINED = 'undefined';
    /**
     * @var string
     */
    protected static $extensions = ['php'];
    /**
     * @var string
     */
    protected $type = self::TYPE_UNDEFINED;
    /**
     * @var string
     */
    protected $patterns = ['\/\/\s*=\s*require\s+{file}'];

    /**
     * @var Parser
     */
    protected $parser;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param Parser $parser
     * @param Config $config
     */
    public function __construct(Parser $parser, Config $config)
    {
        $this->parser = $parser;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public static function getExtensions()
    {
        return static::$extensions;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $file
     * @return string
     */
    public function getPatterns($file = '.*?')
    {
        $result = [];
        foreach ($this->patterns as $p) {
            $result[] = str_replace('{file}', '(' . $file . ')', $p);
        }
        return $result;
    }

    /**
     * @return string
     */
    public function compile()
    {
        if ($this->config->get(Config::C_MINIFY)) {
            return $this->cache(function () {
                return $this->parse();
            });
        }

        return $this->parse();
    }

    /**
     * @param callable $callback
     * @return string
     */
    public function cache(callable $callback)
    {
        if (!is_dir($cache = $this->config->get(Config::C_CACHE_PATH))) {
            mkdir($cache, 0777, true);
        }

        $cacheFile = $this->config->get(Config::C_CACHE_PATH, '/' . md5($this->getSources()));
        if (file_exists($cacheFile)) {
            return file_get_contents($cacheFile);
        }

        $result = $callback();
        file_put_contents($cacheFile, $result);
        return $result;
    }

    /**
     * @return mixed
     */
    protected function getSources()
    {
        return $this->parser->getSources();
    }

    /**
     * @return mixed
     */
    abstract function parse();

    /**
     * @return ScriptSerialize|StyleSerialize
     * @throws \Exception
     */
    public function getSerializer()
    {
        switch ($this->type) {
            case self::TYPE_STYLE:
                return new StyleSerialize($this);
            case self::TYPE_SCRIPT:
                return new ScriptSerialize($this);
        }

        throw new \Exception('Can not find serializer driver for type ' . $this->type);
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return $this->parser->getPath();
    }
}
