<?php
namespace Asset;

use Asset\Config;
use Asset\Helper\SingletonTrait as Singleton;
use Asset\File\Collection;

class Compiler
{
    use Singleton;

    private $_config;

    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    public function make($file)
    {

        $collection = new Collection($this->_config);
        $collection->append(
            $this->_config->find(Config::PATH_SOURCE) . $file
        );

        return $collection->make();
    }
}