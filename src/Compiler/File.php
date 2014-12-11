<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (05.11.2014 12:39)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 
namespace Serafim\Asset\Compiler;

use Serafim\Asset\Driver\PlainDriver;
use Serafim\Asset\Exception\NoDriverException;
use Serafim\Asset\Manifest\Parser;
use Serafim\Asset\Serialize\PlainSerialize;
use SplFileInfo;

class File
{
    protected static $includedFiles = [];

    protected $file;
    protected $configs;
    protected $public;
    protected $driver;
    protected $included = false;

    public function __construct(SplFileInfo $file, $configs)
    {
        if (!in_array($file->getRealPath(), self::$includedFiles)) {
            self::$includedFiles[] = $file->getRealPath();
        } else {
            $this->included = true;
        }

        $this->file     = $file;
        $this->configs  = $configs;
        $this->driver   = $this->makeDriver($file, $configs);
        $this->public   = $this->makePublicName($file, $configs, $this->driver);
    }

    protected function makePublicName(SplFileInfo $file, $configs, $driver)
    {
        $md5  = md5($file->getRealPath());
        $name = str_replace($file->getExtension(), '', $file->getFilename());

        return ($configs['publish'] == 'advanced')
            ?   substr($md5, 0, 2) . '/' .
                substr($md5, 2, 2) . '/' .
                substr($md5, 4, 16) . '-' .
                    $name . $driver->getOutputExtension()
            :
                $md5 . '-' .
                    $name . $driver->getOutputExtension();
    }

    protected function makeDriver(SplFileInfo $file, $configs)
    {
        $extension = $file->getExtension();
        foreach ($configs['drivers'] as $class => $ext) {
            if (in_array($extension, $ext)) {
                return new $class($this);
            }
        }
        return new PlainDriver($this);
    }

    public function getOutputInterface()
    {
        $ext = $this->driver->getOutputExtension();
        if (isset($this->configs['output'][$ext])) {
            $class = $this->configs['output'][$ext];
            return new $class($this);
        }
        return new PlainSerialize($this);
    }

    public function getIncludedFiles()
    {
        return self::$includedFiles;
    }

    public function getIncludedFileNames()
    {
        $result = [];
        $files  = self::$includedFiles;
        foreach ($files as $file) {
            $result[] = basename($file);
        }
        return $result;
    }

    protected function clearCompiledFiles()
    {
        self::$includedFiles = [];
        return $this;
    }


    public function compile($app)
    {
        if ($this->included) { return ''; }

        $source = file_get_contents($this->file->getRealPath());
        $result = $this->driver->compile($source, $app);

        if ($this->driver->hasManifest()) {
            $parser = new Parser($this, $result, $app);
            $result = $parser->getSources();
        }

        return $result;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    public function getFileHeader($expr = null)
    {
        return $this->driver->getFileHeader($expr);
    }

    public function getConfigs()
    {
        return $this->configs;
    }

    public function getSplFileInfo()
    {
        return $this->file;
    }

    public function exists()
    {
        return file_exists($this->getPublicPath());
    }

    public function getPublicPath()
    {
        return $this->configs['public'] . '/' . $this->public;
    }

    public function getPublicUrl()
    {
        return $this->configs['url'] . '/' . $this->public;
    }
}
