<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 15:48
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Compiler;

use SplFileObject;
use LogicException;
use Serafim\Asset\Drivers\DriverInterface;

/**
 * Class File
 * @package Serafim\Asset\Compiler
 */
class File
{
    /**
     * @var SplFileObject
     */
    protected $file;

    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var DriverStorage
     */
    protected $drivers;

    /**
     * @var string
     */
    protected $sources;

    /**
     * @var ManifestReader
     */
    protected $manifest;

    /**
     * Dependency insert whitespace level
     *
     * @var int
     */
    protected $whitespaceLevel = 0;

    /**
     * @param $fileName
     * @param array $paths
     * @param null $sources
     */
    public function __construct($fileName, array $paths = [], $sources = null)
    {
        $this->file  = new SplFileObject($fileName);
        $this->paths = $paths;

        $this->sources = $sources;
        if ($sources === null) {
            $this->sources = file_get_contents($this->file->getPathname());
        }

        // Dependency reader
        $this->manifest = new ManifestReader($this);

        // Attach empty storage
        $this->drivers  = new DriverStorage();
    }

    /**
     * @param int $level
     */
    public function setWhitespaceLevel($level)
    {
        $this->whitespaceLevel = $level;
    }

    /**
     * @return int
     */
    public function getWhitespaceLevel()
    {
        return $this->whitespaceLevel;
    }

    /**
     * @return array
     */
    public function getInputPaths()
    {
        return $this->paths;
    }

    /**
     * @return SplFileObject
     */
    public function getSplFileObject()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getSourceCode()
    {
        $whitespaces = str_repeat(' ', $this->whitespaceLevel);
        return $whitespaces .
            str_replace("\n", "\n" . $whitespaces, $this->sources);
    }

    /**
     * @return string
     */
    public function build()
    {
        $sources = $this->getSourceCode();

        $sources = $this->applyDependencies(
            $sources,
            $this->manifest->getSourceDependencies(),
            function(File $file) {
                return $file->getSourceCode();
            }
        );

        foreach ($this->getPipeline() as $driver) {
            if ($driver) {
                $sources = $driver->build($this->file, $sources);
            }
        }

        $sources = $this->applyDependencies(
            $sources,
            $this->manifest->getCompiledDependencies(),
            function(File $file) { return $file->build(); }
        );

        return $sources;
    }

    /**
     * Inject dependencies inside code
     *
     * @param $sources
     * @param \Traversable $dependencies
     * @param callable $insert
     * @return mixed
     */
    protected function applyDependencies($sources, \Traversable $dependencies, callable $insert)
    {
        foreach ($dependencies as $query => $subDependencies) {
            $joinedCode = '';
            foreach ($subDependencies as $file) {
                $joinedCode .= $insert($file);
            }
            $sources = str_replace($query, $joinedCode, $sources);
        }

        return $sources;
    }

    /**
     * @param DriverStorage $storage
     * @return $this
     */
    public function attachDriverStorage(DriverStorage $storage)
    {
        $this->drivers = $storage;
        return $this;
    }

    /**
     * @return DriverStorage
     */
    public function getDriverStorage()
    {
        return $this->drivers;
    }


    /**
     * @return DriverInterface[]|\Generator
     */
    protected function getPipeline()
    {
        $extensions = explode('.', $this->file->getFilename());
        array_shift($extensions);

        foreach ($extensions as $extension) {
            yield $this->drivers->detectDriver($extension);
        }
    }
}
