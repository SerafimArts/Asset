<?php
namespace Serafim\Asset\Minify;

use Serafim\Asset\Compiler\Minify\JSMinPlus;

class JsNativeMinify implements MinifyInterface
{
    public function minify($code)
    {
        return JSMinPlus::minify($code);
    }
}
