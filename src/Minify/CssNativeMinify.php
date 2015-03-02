<?php
namespace Serafim\Asset\Minify;

use Serafim\Asset\Compiler\Minify\YuiCssCompressor;

class CssNativeMinify implements MinifyInterface
{
    protected $compressor;

    public function __construct()
    {
        $this->compressor = new YuiCssCompressor();
        $this->compressor->set_max_execution_time(120);
    }

    public function minify($code)
    {
        return $this->compressor->run($code, 2000);
    }
}
