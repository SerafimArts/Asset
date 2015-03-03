<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (05.11.2014 14:28)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Driver;

use Serafim\Asset\Compiler\File;
use Serafim\Asset\Events;

/**
 * Class AbstractDriver
 * @package Serafim\Asset\Driver
 */
abstract class AbstractDriver
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    /**
     * @var File
     */
    protected $container;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file->getSplFileInfo();
        $this->container = $file;
    }

    /**
     * @param $app
     * @param callable $make
     * @return mixed
     */
    protected function cache($app, callable $make)
    {
        $hash = 'assets@' . md5_file($this->file->getRealPath());

        #$timeout    = 10;
        return $app['cache']->rememberForever($hash, function () use ($make, $app) {
            $app['events']->fire(Events::COMPILE, $this->container);

            return $make();
        });
    }

    /**
     * @param $sources
     * @param $app
     * @return mixed
     */
    public function compile($sources, $app)
    {
        return $sources;
    }

    /**
     * @param null $line
     * @return string
     */
    public function getFileHeader($line = null)
    {
        return
            '/** ' . "\n" .
            ' * @line ' . $line . "\n" .
            ' * @file ' . $this->file->getRealPath() . "\n" .
            ' */' . "\n";
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasManifest()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getOutputExtension()
    {
        return 'txt';
    }
}
