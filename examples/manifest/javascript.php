<?php
require '../../../vendor/autoload.php';


$c = new Asset\Compiler([
    Asset\Config::CACHE   => __DIR__ . '/assets/',
    Asset\Config::URL     => '/assets/'
]);

$m = $c->manifest('application.js');
print_r($m);