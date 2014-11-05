<?php
namespace Serafim\Asset\Compiler;

use Serafim\Asset\Manifest\Parser;
use SplFileInfo;

class File
{
    protected $file;
    protected $config;
    protected $spl;
    protected $public;
    protected $driver;
    protected $sources;

    public function __construct(SplFileInfo $file, $config)
    {
        $this->config   = $config;
        $this->spl      = $file;
        $this->driver   = $this->getDriver();
        $this->public   = $this->getAssetPath();
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getDriver()
    {
        if ($this->driver) {
            return $this->driver;
        }

        $ext = $this->getExtension();
        foreach ($this->config['drivers'] as $driver => $extensions) {
            if (in_array($ext, $extensions)) {
                $driver::check();
                return $driver;
            }
        }

        return '\\Serafim\\Asset\\Driver\\PlainDriver';
    }

    public function exists()
    {
        return file_exists($this->public->target);
    }

    public function build()
    {
        if (!$this->sources) {
            $driver = new $this->driver;
            $sources = $driver->compile($this->spl);
            $this->sources = (new Parser($this, $sources))
                ->getSources();
        }
        return $this->sources;
    }

    public function getSplFileInfo()
    {
        return $this->spl;
    }

    public function getExtension()
    {
        return $this->spl->getExtension();
    }

    public function getName()
    {
        return explode('.', $this->spl->getFilename())[0];
    }

    public function getAssetPath()
    {
        if ($this->public) {
            return $this->public;
        }

        $md5 = md5($this->spl->getRealPath());

        $path = ($this->config['publish'] == 'advanced')
            ?   substr($md5, 0, 2) . '/' .
                substr($md5, 2, 2) . '/' .
                substr($md5, 4, 16) . '-' .
                    $this->spl->getFilename()
            :
                $md5 . '-' . $this->spl->getFilename();

        return (object)[
            'url'       => $this->config['path']['url'] . '/' . $path,
            'target'    => $this->config['path']['public'] . '/' . $path
        ];
    }
}