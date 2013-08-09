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
     * @return string
     */
    public function compile($files)
    {
        $files = is_array($files) ? $files : [$files];
        foreach ($files as $file) {
            Registry::push($file);
        }
        return Registry::compile($this->_config);
    }
}