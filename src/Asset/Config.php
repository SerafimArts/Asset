<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:06
 * @copyright 2008-2013 RuDev
 * @package Config.php
 * @since 1.0
 */
namespace Asset;

/**
 * Class Config
 * @package Asset
 */
class Config
{
    /**
     * @var array
     */
    private $_config = [
        'cache' => false,
        'url'   => '/'
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