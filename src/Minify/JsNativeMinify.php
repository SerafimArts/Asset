<?php
namespace Serafim\Asset\Minify;

use JSMinPlus;

class JsNativeMinify implements MinifyInterface
{
    public function minify($code)
    {
        return JSMinPlus::minify($code);
    }
}
