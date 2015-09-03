<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 20:07
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Compiler;

use LogicException;
use InvalidArgumentException;
use Symfony\Component\Finder\Finder;

/**
 * Class FileCollection
 * @package Serafim\Asset\Compiler
 */
class FileCollection
{
    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var array
     */
    protected $files = [];

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
    protected $query;

    /**
     * @param $query
     * @param array $paths
     * @throws InvalidArgumentException
     */
    public function __construct($query, array $paths)
    {
        $query          = str_replace('\\', '/', $query);
        $this->query    = $query;
        $this->paths    = $paths;
        $this->finder   = (new Finder)
            ->files()
            ->in($paths);

        if (strpos($query, '**/') >= 0) {
            $query = str_replace('**/', '', $query);
        } else {
            $this->finder->depth(0);
        }

        $this->finder->name($query);
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
     * @return File[]
     * @throws LogicException
     */
    public function getFiles()
    {
        if (!$this->drivers) {
            // Attach empty driver storage if not exists
            $this->drivers = new DriverStorage();
        }

        if ($this->files === []) {
            foreach ($this->finder as $file) {
                $this->files[] = (new File($file->getPathname(), $this->paths))
                    ->setHeader('Inject ' . $this->query)
                    ->attachDriverStorage($this->drivers);
            }
        }

        return $this->files;
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->getFiles() as $file) {
            $callback($file);
        }
        return $this;
    }
}
