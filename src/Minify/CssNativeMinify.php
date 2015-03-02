<?php
namespace Serafim\Asset\Minify;

use CssMin;

class CssNativeMinify implements MinifyInterface
{
    public function minify($code)
    {
        return CssMin::minify($code);
    }
}
