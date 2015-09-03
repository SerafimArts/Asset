<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 15:54
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Serafim\Asset\Compiler;
use Serafim\Asset\Drivers\CssDriver;
use Serafim\Asset\Drivers\ScssDriver;
use Serafim\Asset\Drivers\JavaScriptDriver;
use Serafim\Asset\Drivers\TypeScriptDriver;
use Serafim\Asset\Drivers\CoffeeScriptDriver;


require __DIR__ . '/../vendor/autoload.php';

try {

    $compiler = (new Compiler)
        ->attachDriver(new JavaScriptDriver,    ['js'])
        ->attachDriver(new CoffeeScriptDriver,  ['coffee'])
        ->attachDriver(new TypeScriptDriver,    ['ts'])
        ->attachDriver(new CssDriver,           ['css'])
        ->attachDriver(new ScssDriver(),        ['scss'])
        ->addInputPaths(__DIR__ . '/stubs')
        ->build('test.scss');

} catch (Exception $e) {
    echo $e->getMessage() . "\n";
    echo 'In file ' . $e->getFile() . ':' . $e->getLine() . "\n\n";

    echo $e->getTraceAsString();
}
