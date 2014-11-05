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

use SplFileInfo;

abstract class AbstractDriver
{
    protected $file;

    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }


    protected function cache($driver, callable $make)
    {
        $hash = 'assets@' . md5_file($this->file->getRealPath());
        return $driver->rememberForever($hash, function() use ($make) {
            return $make();
        });
    }

    public function compile($sources, $cache)
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
