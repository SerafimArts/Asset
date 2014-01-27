<?php
namespace Asset\Driver;

use Asset\Driver\DriverInterface;
use Asset\File\Collection;
use Asset\Config;
use SplFileObject;

abstract class AbstractDriver
    implements DriverInterface
{
    const TYPE_CSS  = 'css';
    const TYPE_JS   = 'js';

    protected $type = 'undefined';
    protected $collection;
    protected $file     = null;
    protected $source   = '';
    protected $result;

    public function __construct(Collection $items, SplFileObject $file)
    {
        $this->collection  = $items;
        $this->file         = $file;
        while (!$this->file->eof()) {
            $this->source .= $this->file->fgets();
        }
        $this->getDepending();
    }

    public function getDepending()
    {
        preg_match_all('#^//\s*=\s*require\s*(.*?)$#misu', $this->source, $depending);
        $this->setDepending($depending[1]);
    }

    public function setDepending($depending)
    {
        foreach ($depending as $d) {
            $d = (strstr($d, '*'))
                ? glob($this->file->getPath() . DIRECTORY_SEPARATOR . $d)
                : [$this->file->getPath() . DIRECTORY_SEPARATOR . $d];
            foreach ($d as $i) {
                $this->collection->append($i);
            }
        }
    }

    protected function cache($source, callable $cb)
    {
        $path = $this->collection->getConfig()->find(Config::PATH_TEMP) . md5($source);
        if (file_exists($path)) {
            return file_get_contents($path);
        } else {
            $result = $cb();
            @mkdir(dirname($path), true);
            file_put_contents($path, $result);
            return $result;
        }
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getFile()
    {
        return $this->file;
    }
}