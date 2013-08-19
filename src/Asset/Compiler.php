<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 09.08.13 5:17
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset;

use \Asset\Exception\CacheException;
use \Asset\File\Registry;

/**
 * Class Compiler
 * @package Asset
 */
class Compiler
{
    /**
     * @var Config
     */
    private $_config;

    /**
     * @param array $conf
     * @throws CacheException
     */
    public function __construct(array $conf)
    {
        $this->_config = new Config($conf);
        if (!$this->_config->get('cache')) {
            throw new CacheException('Undefined cache path.');
        }
    }

    /**
     * @param $files
     * @param null $postfix
     * @return string
     */
    public function compile($files, $postfix = null)
    {
        Registry::flush();
        $files = is_array($files) ? $files : [$files];
        foreach ($files as $file) {
            Registry::push($this->_config, $file);
        }
        return Registry::compile($this->_config, $postfix);
    }

    /**
     * @param $file
     * @return string
     */
    public function manifest($file)
    {
        $manifest   = new Manifest($this->_config, $file, $this);
        $files      = $manifest->compile();
        array_unshift($files, $file);
        return $this->compile(
            $files,
            pathinfo($file)['filename']
        );
    }
}
