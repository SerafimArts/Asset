<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:06
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset;

/**
 * Class Config
 * @package Asset
 */
class Config
{
    const CACHE = 'cache';
    const URL   = 'url';
    const BASE_PATH = 'base';
    const ENV   = 'env';
    const ENV_DEVELOPMENT   = 'dev';
    const ENV_PRODUCTION    = 'prod';

    /**
     * @var array
     */
    private $_config = [
        self::CACHE => false,
        self::URL   => '/',
        self::ENV   => self::ENV_DEVELOPMENT,
        self::BASE_PATH => ''
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = array_merge($this->_config, $config);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->_config);
    }

    /**
     * @param $conf
     * @return null
     */
    public function get($conf)
    {
        if (isset($this->_config[$conf])) {
            return $this->_config[$conf];
        }
        return null;
    }
}