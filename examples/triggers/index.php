<?php
require '../../../vendor/autoload.php';

$c = new Asset\Compiler([
    Asset\Config::CACHE   => __DIR__ . '/assets/',
    Asset\Config::URL     => '/assets/'
]);

/**
 * Minify CSS
 */
Asset\Trigger::css(function($source){
    return Asset\Helper\CssMin::minify($source);
});

/**
 * Minify JS
 */
Asset\Trigger::js(function($source){
    return \JSMin::minify($source);
});

$m = $c->compile(['scripts/*/*.coffee', 'styles/*/*.scss']);
print_r($m);