<?php
namespace Serafim\Asset\Minify;

/**
 * Interface MinifyInterface
 * @package Serafim\Asset\Minify
 */
interface MinifyInterface
{
    /**
     * @param $code
     * @return mixed
     */
    public function minify($code);
}
