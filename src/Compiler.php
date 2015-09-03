<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 15:38
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset;

use Serafim\Asset\Compiler\DriverStorage;
use Serafim\Asset\Compiler\File;
use Serafim\Asset\Compiler\FileCollection;
use Serafim\Asset\Drivers\DriverInterface;
use SplFileObject;
use LogicException;

/**
 * Class Compiler
 * @package Serafim\Asset
 */
class Compiler
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * @var DriverStorage
     */
    protected $drivers;

    /**
     * @return Compiler
     */
    public function __construct()
    {
        $this->drivers = new DriverStorage();
    }

    /**
     * @param DriverInterface $driver
     * @param array $extensions
     * @return $this
     */
    public function attachDriver(DriverInterface $driver, array $extensions)
    {
        $this->drivers->attach($driver, $extensions);
        return $this;
    }

    /**
     * @param $paths
     * @return $this
     * @throws LogicException
     */
    public function addInputPaths($paths)
    {
        foreach ((array)$paths as $path) {
            if (!is_dir($path)) {
                $error = sprintf('%s is not a valid directory', $path);
                throw new LogicException($error);
            }

            $this->paths[] = $path;
        }

        return $this;
    }

    /**
     * @param $fileName
     * @return $this
     * @throws LogicException
     */
    public function build($fileName)
    {
        $collection = (new FileCollection($fileName, $this->paths))
            ->attachDriverStorage($this->drivers);

        if (!count($collection->getFiles())) {
            $message = sprintf(
                'File "%s" was not found in [%s]',
                $fileName,
                implode(', ', array_map(function($item){ return '"' . realpath($item) . '"'; }, $this->paths))
            );
            throw new LogicException($message);
        }

        foreach ($collection->getFiles() as $file) {
            $result = $file->build();

            echo str_repeat('=', 100) . "\n";
            echo $result;
            echo str_repeat('=', 100) . "\n";
        }


        return $this;
    }


}
