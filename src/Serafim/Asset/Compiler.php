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

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Serafim\Asset\Exception\FileNotFoundException;
use Serafim\Asset\Manifest\Parser;
use Symfony\Component\Finder\SplFileInfo;

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
        $path = $this->config['path']['sources'] . '/' . $file;
        if (!file_exists($path)) {
            throw new FileNotFoundException("File ${file} not found.");
        }


        $spl        = new SplFileInfo($path, dirname($file), $file);
        $driver     = $this->getDriver($spl->getFilename());
        $serialize  = $driver::getSerializationInterface($spl);

        $asset      = $this->getAssetFilePath($spl, $serialize->getExtension());

        if (file_exists($asset->target) && $this->config['cache']) {
            $sources = file_get_contents($asset->target);
        } else {
            if (!$driver::parseManifest()) {
                $sources = $this->build($spl, new $driver);
            } else {
                $manifest = new Parser(
                    $this,
                    new SplFileInfo($path, dirname($spl), $spl)
                );
                $sources = $manifest->parse($driver);
            }

            if (!is_dir(dirname($asset->target))) {
                mkdir(dirname($asset->target), 0777, true);
            }
            file_put_contents($asset->target, $sources);
            $this
                ->app['events']
                ->fire(self::EVENT_PUBLISH, $asset);
        }

        $serialize->setUrl($asset->url);
        $serialize->setContent($sources);

        return $serialize;
    }

    /**
     * @param SplFileInfo $file
     * @param $extension
     * @return object
     */
    protected function getAssetFilePath(SplFileInfo $file, $extension)
    {
        $md5  = md5($file->getContents());
        $name = str_replace('.' . $file->getExtension(), '', $file->getFilename());
        $path =
            substr($md5, 0, 2)  . '/' .
            substr($md5, 2, 2)  . '/' .
            substr($md5, 4, 16) . '-' .
            $name . $extension;

        return (object)[
            'url'       => $this->config['path']['url'] . '/' . $path,
            'target'    => $this->config['path']['public'] . '/' . $path
        ];
    }


    /**
     * @param SplFileInfo $file
     * @param $driver
     * @return mixed
     */
    public function build(SplFileInfo $file, $driver)
    {
        $key = md5($file->getContents());
        return $this
            ->app['cache']
            ->rememberForever($key, function() use($file, $driver) {
                $this
                    ->app['events']
                    ->fire(Compiler::EVENT_COMPILE, $file);
                return $driver
                    ->compile($file);
            });
    }

    /**
     * @param $filename
     * @return string
     */
    public function getDriver($filename)
    {
        $ext = (new \SplFileInfo($filename))->getExtension();
        foreach ($this->config['drivers'] as $driver => $extensions) {
            if (in_array($ext, $extensions)) {
                $driver::check();
                return $driver;
            }
        }

        return '\\Serafim\\Asset\\Driver\\PlainDriver';
    }
}
