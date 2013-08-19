<?php
require '../../../vendor/autoload.php';

$c = new Asset\Compiler([
    Asset\Config::CACHE   => __DIR__ . '/assets/',
    Asset\Config::URL     => '/assets/'
]);

/**
 * Announcement:
 * "path/to/file.extension"     - include one file
 * "path/mask/*.extension"      - include some files by mask
 * "path/to/*\/*.extension"     - include files into "path/to/" recursive by mask "*.extension"
 */
$m = $c->compile(['scripts/*/*.coffee', 'styles/*/*.scss']);
print_r($m);