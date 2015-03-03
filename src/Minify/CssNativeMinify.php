<?php
namespace Serafim\Asset\Minify;

use Serafim\Asset\Compiler\Minify\YuiCssCompressor;

/**
 * Class CssNativeMinify
 * @package Serafim\Asset\Minify
 */
class CssNativeMinify implements MinifyInterface
{
    /**
     * @var YuiCssCompressor
     */
    protected $compressor;

    public function __construct()
    {
        $this->compressor = new YuiCssCompressor();
        $this->compressor->set_max_execution_time(120);
    }

    /**
     * @param $code
     * @return string
     */
    public function minify($code)
    {
        return $this->compressor->run($code, 2000);
    }
}
