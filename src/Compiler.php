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

use Serafim\Asset\Exception\FileNotFoundException;
use Serafim\Asset\Compiler\File;
use Serafim\Asset\Exception\DoublePathException;

/**
 * Class Compiler
 * @package Serafim\Asset
 */
class Compiler
{
    const EVENT_BOOT    = 'asset.boot';
    const EVENT_COMPILE = 'asset.compile';
    const EVENT_PUBLISH = 'asset.publish';

    /**
     * @var
     */
    protected $app;

    /**
     * @var
     */
    protected $config;

    /**
     * @param $app
     * @param $config
     */
    public function __construct($app, $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->app;
    }

    /**
     * @param $file
     * @param array $options
     * @return array
     * @throws FileNotFoundException
     */
    public function make($file, array $options = [])
    {
        $spl = $this->search($file, $this->config);
        $file = new File($spl, $this->config);


        if (!$this->config['cache'] || !$file->exists()) {
            $sources = $file->build();
            $path    = $file->getAssetPath();
            if (!is_dir(dirname($path->target))) {
                mkdir(dirname($path->target), 0777, true);
            }
            file_put_contents($path->target, $sources);
        }

        $driver = $file->getDriver();
        return $driver::getSerializationInterface($file);
    }

    protected function search($file, $config)
    {
        $realpath = null;
        foreach($config['paths'] as $path) {
            $temp = $path . '/' . $file;
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
            throw new FileNotFoundException("File ${file} not found.");
        }

        return new \SplFileInfo($realpath);
    }
}
