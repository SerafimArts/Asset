<?php
namespace Asset;

use Asset\Helper\SingletonTrait as Singleton;

class Config
{
    use Singleton;

    const PATH_SOURCE = 'sources';
    const PATH_PUBLIC = 'public';
    const PATH_TEMP   = 'temp';
    const PATH_URL    = 'url';

    private $_configs;

    public function __construct(array $configs)
    {
        $this->_configs = $configs;
    }

    public function find($name)
    {
        return $this->$name;
    }

    public function __get($var)
    {
        if (isset($this->_configs[$var])) {
            $conf       = $this->_configs[$var];
            $lastChar   = substr($conf, strlen($conf) - 1, 1);
            if ($lastChar == '/' || $lastChar == '\\') {
                return $conf;
            }
            return $conf . DIRECTORY_SEPARATOR;
        }
        return null;
    }
}