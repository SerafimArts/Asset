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
use Serafim\Asset\Manifest\Parser;
use Serafim\Asset\Serialize\PlainSerialize;
use SplFileInfo;

/**
 * Class File
 * @package Serafim\Asset\Compiler
 */
class File
{
    /**
     * @var array
     */
    protected static $includedFiles = [];

    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var
     */
    protected $configs;

    /**
     * @var string
     */
    protected $public;

    /**
     * @var PlainDriver
     */
    protected $driver;

    /**
     * @var bool
     */
    protected $included = false;

    /**
     * @param SplFileInfo $file
     * @param $configs
     */
    public function __construct(SplFileInfo $file, $configs)
    {
        if (!in_array($file->getRealPath(), self::$includedFiles)) {
            self::$includedFiles[] = $file->getRealPath();
        } else {
            $this->included = true;
        }

        $this->file = $file;
        $this->configs = $configs;
        $this->driver = $this->makeDriver($file, $configs);
        $this->public = $this->makePublicName($file, $configs, $this->driver);
    }

    /**
     * @param $code
     * @return mixed
     */
    public function minify($code)
    {
        $ext = $this->driver->getOutputExtension();

        if ($this->configs['minify']['enable'] &&
            in_array($ext, array_keys($this->configs['minify']))
        ) {

            $class = $this->configs['minify'][$ext];
            $instance = new $class;

            return $instance->minify($code);
        }

        return $code;
    }

    /**
     * @param SplFileInfo $file
     * @param $configs
     * @param $driver
     * @return string
     */
    protected function makePublicName(SplFileInfo $file, $configs, $driver)
    {
        $md5 = md5($file->getRealPath());
        $name = str_replace($file->getExtension(), '', $file->getFilename());

        return ($configs['publish'] == 'advanced')
            ? substr($md5, 0, 2) . '/' .
            substr($md5, 2, 2) . '/' .
            substr($md5, 4, 16) . '-' .
            $name . $driver->getOutputExtension()
            :
            $md5 . '-' .
            $name . $driver->getOutputExtension();
    }

    /**
     * @param SplFileInfo $file
     * @param $configs
     * @return PlainDriver
     */
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

    /**
     * @return PlainSerialize
     */
    public function getOutputInterface()
    {
        $ext = $this->driver->getOutputExtension();
        if (isset($this->configs['output'][$ext])) {
            $class = $this->configs['output'][$ext];

            return new $class($this);
        }

        return new PlainSerialize($this);
    }

    /**
     * @return array
     */
    public function getIncludedFiles()
    {
        return self::$includedFiles;
    }

    /**
     * @return array
     */
    public function getIncludedFileNames()
    {
        $result = [];
        $files = self::$includedFiles;
        foreach ($files as $file) {
            $result[] = basename($file);
        }

        return $result;
    }

    public static function clearCompiledFiles()
    {
        self::$includedFiles = [];
    }

    /**
     * @param $app
     * @return mixed|string
     */
    public function compile($app)
    {
        if ($this->included) {
            return '';
        }

        $source = '';

        if (!is_file($this->file->getRealPath())) {
            throw new \LogicException(sprintf(
                'Invalid file "%s", file not found.',
                $this->file->getPathname()
            ));
        }

        // Add \n
        $source = file_get_contents($this->file->getRealPath()) . "\n";


        $result = $this->driver->compile($source, $app);

        if ($this->driver->hasManifest()) {
            $parser = new Parser($this, $result, $app);
            $result = $parser->getSources();
        }

        return $result;
    }

    /**
     * @return PlainDriver
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param null $expr
     * @return string
     */
    public function getFileHeader($expr = null)
    {
        return $this->driver->getFileHeader($expr);
    }

    /**
     * @return mixed
     */
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @return SplFileInfo
     */
    public function getSplFileInfo()
    {
        return $this->file;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return is_file($this->getPublicPath());
    }

    /**
     * @return string
     */
    public function getPublicPath()
    {
        return $this->configs['public'] . '/' . $this->public;
    }

    /**
     * @return string
     */
    public function getPublicUrl()
    {
        return $this->configs['url'] . '/' . $this->public .
                '?v=' . $this->file->getMTime();
    }
}
