<?php
require '../../../vendor/autoload.php';

/**
 * Scripts
 */
$scripts = (new Asset\Compiler([
    Asset\Config::CACHE   => __DIR__ . '/public/js/', // cache path (save path)
    Asset\Config::URL     => 'http://mysite.org/js/'  // url
]))
->compile([
    'scripts/test/*.coffee',        // relative path
    __DIR__ . '/scripts/*.coffee'   // absolute path
]);


/**
 * Styles
 */
$styles = (new Asset\Compiler([
    Asset\Config::CACHE   => __DIR__ . '/public/css/',  // cache path
    Asset\Config::URL     => '/css/',                   // url
    Asset\Config::BASE_PATH => __DIR__ . '/styles'      // absolute base path for all styles
]))
    ->compile('*/*.scss'); // require all scss by mask


print_r($scripts);
print_r($styles);