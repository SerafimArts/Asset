<?php
namespace Asset\File\Serialize;

use Asset\Config;
use Asset\Driver\AbstractDriver;

abstract class AbstractSerialize
{
    protected $driver;
    protected $config;
    protected $source;

    public function __construct(AbstractDriver $driver, Config $conf, $src)
    {
        $this->driver = $driver;
        $this->config = $conf;
        $this->source = $src;
    }

    protected function cache()
    {
        $source = $this->source;
        $public = $this->config->find(Config::PATH_PUBLIC);
        $name   =  md5($source)
            . '-' . $this->driver->getFile()->getFilename() . '.' . $this->driver->getType();

        if (!file_exists($public . $name)) {
            @mkdir(dirname($public . $name), true);
            file_put_contents($public . $name, $source);
        }
        return $this->config->find(Config::PATH_URL) . $name;
    }

    protected function argsToAttrs($args)
    {
        $result = '';
        foreach ($args as $name => $val) {
            $result .= $name . '="' . $val . '" ';
        }
        return $result;
    }

    public function toSource($args = [])
    {
        return $this->source;
    }

    public function toLink($args = [])
    {
        return $this->source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function __toString()
    {
        return $this->toLink();
    }

}