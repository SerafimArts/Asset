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
use Carbon\Carbon;

abstract class AbstractDriver
{
    protected $file;
    protected $container;

    public function __construct(File $file)
    {
        $this->file = $file->getSplFileInfo();
        $this->container = $file;
    }


    protected function cache($app, callable $make)
    {
        $hash       = 'assets@' . md5_file($this->file->getRealPath());
        #$timeout    = 10;
        return $app['cache']->rememberForever($hash, function() use ($make, $app) {
            $app['events']->fire(Events::COMPILE, $this->container);
            return $make();
        });
    }

    public function compile($sources, $app)
    {
        return $sources;
    }

    public function getFileHeader($line = null)
    {
        return
            '/** ' . "\n" .
            ' * @line ' . $line  . "\n" .
            ' * @file ' . $this->file->getRealPath() . "\n" .
            ' */' . "\n";
    }

    public function validate()
    {
        return true;
    }

    public function hasManifest()
    {
        return true;
    }

    public function getOutputExtension()
    {
        return 'txt';
    }
}
