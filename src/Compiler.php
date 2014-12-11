<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (14.10.2014 19:55)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Serafim\Asset;

use Serafim\Asset\Compiler\File;
use Serafim\Asset\Compiler\Publisher;
use Serafim\Asset\Exception\FileNotFoundException;
use Serafim\Asset\Exception\DoublePathException;
use SplFileInfo;

/**
 * Class Compiler
 * @package Serafim\Asset
 */
class Compiler
{
    /**
     * @var
     */
    protected $app;

    /**
     * @var
     */
    protected $configs;

    /**
     * @param $app
     * @param $configs
     */
    public function __construct($app, $configs)
    {
        $this->app = $app;
        $this->configs = $configs;
    }

    /**
     * @param $path
     * @param array $options
     * @return array
     * @throws FileNotFoundException
     */
    public function make($path, array $options = [])
    {
        File::clearCompiledFiles();
        $file = new File(
            $this->search($path, $this->configs),
            $this->configs
        );

        if (!$this->configs['cache'] || !$file->exists()) {
            $publisher = new Publisher($file, $this->configs, $this->app);
            $publisher->publish();

            $this->app['events']->fire(Events::PUBLISH, $file);
        }

        return $file->getOutputInterface();
    }


    protected function search($fpath, $configs)
    {
        $realpath = null;
        foreach($configs['paths'] as $path) {
            $temp = $path . '/' . $fpath;
            if (file_exists($temp)) {
                if ($realpath) {
                    throw new DoublePathException(
                        "File found in ${temp} but file already exists in ${realpath}"
                    );
                }
                $realpath = $temp;
            }
        }

        if (!$realpath) {
            throw new FileNotFoundException("File ${fpath} not found.");
        }

        return new SplFileInfo($realpath);
    }
}
