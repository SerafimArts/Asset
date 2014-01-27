<?php
namespace Asset\File;

use SplFileObject;
use Asset\Config;
use Asset\Extension;
use Asset\File\Serialize;

class Collection
{
    private $_config;
    private $_files = [];
    private $_paths = [];
    private $_type;

    public function __construct(Config $config)
    {
        $this->_config = $config;
    }

    public function append($path)
    {
        $path = trim(
            str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $path)
        );
        if (!file_exists($path)) {
            $collection = isset($this->_files[0])
                ? $this->_files[0]->getPath()
                : 'undefined';
            throw new \Exception("Can not find file \"${path}\" in \"${collection}\" collection");
        }
        if (in_array(realpath($path), $this->_paths)) { return; }
        $this->_paths[] = realpath($path);

        $file           = new SplFileObject($path);
        $name           = $this->getDriver($file);
        $driver         = new $name($this, $file);
        $this->_files[] = $driver;

        if (!$this->_type) {
            $this->_type = $driver->getType();
        }
    }

    public function make()
    {
        $sources = '';
        foreach ($this->_files as $file) {
            $file->make();
            $sources .= $file->getResult();
        }

        $serialize = '\\Asset\\File\\Serialize\\' . ucfirst($this->_type) . 'Serialize';
        return new $serialize(
            $this->_files[0],
            $this->_config,
            $sources
        );
    }

    public function getConfig()
    {
        return $this->_config;
    }

    protected function getDriver(SplFileObject $file)
    {
        $ext = $file->getExtension();
        return Extension::find($ext);
    }
}