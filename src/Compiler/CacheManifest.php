<?php
namespace Serafim\Asset\Compiler;

use App;
use Illuminate\Support\Facades\Log;
use Serafim\Asset\Compiler;

class CacheManifest
{
    const NAME = '/%s.appcache';

    protected static $enabled = false;

    protected $config;
    protected $files = [];

    protected $cache    = [];
    protected $network  = ['*'];
    protected $fallback = [];


    public function __construct(array $files, $config)
    {
        $this->config = $config;
        $this->files  = $files;

        foreach ($this->files as $f) {
            $this->cache[] = self::removeHost($f->getPublicUrl());
        }
    }


    public static function getName()
    {
        return sprintf(self::NAME, 'manifest');
    }


    public static function getOutputPath($config)
    {
        return $config['public'] . self::getName();
    }


    public static function getOutputUrl($config)
    {
        $url    = self::removeHost($config['url']);

        $hash = file_exists(self::getOutputPath($config))
            ? filemtime(self::getOutputPath($config))
            : '';

        return $url . self::getName() . '?v=' . $hash;
    }

    public function build()
    {
        if (!$this->config['cache']) { return; }
        if ($this->isActual())       { return; }

        $out = self::getOutputPath($this->config);

        if (!is_dir(dirname($out))) {
            mkdir(dirname($out, 0777, true));
        }
        if (file_exists($out)) {
            unlink($out);
        }

        foreach ($this->files as $file) {
            $this->relations($file);
        }

        file_put_contents($out, $this->getContents());
    }

    public function relations(File $file) {
        preg_match_all('/url\((.*?)\)/', file_get_contents($file->getPublicPath()), $matches);

        for ($i=0; $i<count($matches[1]); $i++) {
            $link = str_replace(["'", '"'], ['',''], $matches[1][$i]);
            $link = self::removeHost($link);
            if ($link) {
                if (strstr($link, 'base64')) {
                    continue;
                }
                $this->cache[] = $link;
            }
        }
    }

    public function getContents()
    {
        $result  = "CACHE MANIFEST\n\n";

        $section = function($name, $items) {
            $result  = '';
            $result .= strtoupper($name) . ":\n";
            foreach ($items as $item) {
                $result .= $item . "\n";
            }
            $result .= "\n";
            return $result;
        };

        $result .= $section('Cache',    $this->cache);
        $result .= $section('Fallback', $this->fallback);
        $result .= $section('Network',  $this->network);

        return $result;
    }

    public static function removeHost($url)
    {
        return parse_url($url)['path'];
    }

    protected function isActual()
    {
        if (!file_exists($this->getOutputPath($this->config))) {
            return false;
        }

        $max = 0;
        foreach ($this->files as $f) {
            $fileTime = $f->getSplFileInfo()->getMTime();
            if ($fileTime < $max) { $max = $fileTime; }
        }

        return !($max > filemtime($this->getOutputPath($this->config)));
    }
}
