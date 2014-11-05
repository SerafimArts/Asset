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
use SplFileInfo;
use Serafim\Asset\Driver\AbstractDriver;

class ImageDriver extends AbstractDriver
{
    protected static $driver;

    public function compile($sources, $app)
    {
        if (!self::$driver) {
            self::$driver = new ImageManager(['driver' => 'gd']);
        }
        return $this->cache($app, function() use($sources) {
            return self::$driver
                ->make($sources)
                ->encode($this->file->getExtension(), 70);
        });
    }

    public function hasManifest()
    {
        return false;
    }

    public function getOutputExtension()
    {
        return $this->file->getExtension();
    }
}
