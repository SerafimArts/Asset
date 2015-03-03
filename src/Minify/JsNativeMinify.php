<?php
namespace Serafim\Asset\Minify;

use Serafim\Asset\Compiler\Minify\JSMinPlus;

/**
 * Class JsNativeMinify
 * @package Serafim\Asset\Minify
 */
class JsNativeMinify implements MinifyInterface
{
    /**
     * @param $code
     * @return bool|string
     */
    public function minify($code)
    {
        return JSMinPlus::minify($code);
    }
}
