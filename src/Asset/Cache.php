<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:05
 * @copyright 2008-2013 RuDev
 * @package Cache.php
 * @since 1.0
 */
namespace Asset;

use Asset\Exception\CacheException;

/**
 * Class Cache
 * @package Mirror\Tokenizer
 */
class Cache
{
    /**
     * @var Config
     */
    private $_config;

    /**
     * @var bool
     */
    private $_cache = false;

    /**
     * @var string
     */
    private $_extension = '';

    /**
     * @param Config $config
     * @param        $extension
     */
    public function __construct(\Asset\Config $config, $extension)
    {
        $this->_extension   = $extension;
        $this->_config      = $config;

        $cache = $this->_config->get('cache');
        $this->_cache = (bool)$cache;
    }

    /**
     * @param          $source
     * @param callable $cb
     * @return bool|string
     */
    public function check($source, callable $cb)
    {
        if (!$this->_cache) { return false; }
        $path = realpath($source);

        if ($this->has($path)) {
            $this->get($path);
        } else {
            $this->set($path, $cb());
        }
        return $this->_config->get('url') .
        pathinfo($this->_hash($source), PATHINFO_BASENAME);
    }

    /**
     * @param $source
     * @return bool
     */
    public function has($source)
    {
        $this->_checkCache();
        $cache  = $this->_hash($source);
        if (
            !file_exists($cache) ||
            filemtime(realpath($source)) > filemtime($cache)
        ) { return false; }
        return true;
    }

    /**
     * @param $source
     * @param $data
     * @return mixed
     */
    public function set($source, $data)
    {
        $this->_checkCache();
        $cache  = $this->_hash($source);
        if (!is_dir(dirname($cache))) { mkdir(dirname($cache), 0777, true); }
        file_put_contents($cache, $data);
        return $data;
    }

    /**
     * @param $source
     * @return string
     */
    public function get($source)
    {
        $this->_checkCache();
        return file_get_contents(
            $this->_hash($source)
        );
    }

    /**
     * @param $name
     * @return string
     */
    private function _hash($name)
    {
        return $this->_config->get('cache') . md5($name) . '-' .
        pathinfo($name, PATHINFO_FILENAME) . '.' . $this->_extension;
    }

    /**
     * @throws Exception\CacheException
     */
    private function _checkCache()
    {
        if (!$this->_cache) {
            throw new CacheException('Undefined path for compiled sources');
        }
    }
}