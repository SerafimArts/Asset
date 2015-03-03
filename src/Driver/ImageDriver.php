<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (05.11.2014 13:35)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Driver;

use Intervention\Image\ImageManager;

/**
 * Class ImageDriver
 * @package Serafim\Asset\Driver
 */
class ImageDriver extends AbstractDriver
{
    /**
     * @var
     */
    protected static $driver;

    /**
     * @param $sources
     * @param $app
     * @return mixed
     */
    public function compile($sources, $app)
    {
        if (!self::$driver) {
            self::$driver = new ImageManager(['driver' => 'gd']);
        }

        return $this->cache($app, function () use ($sources) {
            return self::$driver
                ->make($sources)
                ->encode($this->file->getExtension(), 70);
        });
    }

    /**
     * @return bool
     */
    public function hasManifest()
    {
        return false;
    }

    /**
     * @return string
     */
    public function getOutputExtension()
    {
        return $this->file->getExtension();
    }
}
