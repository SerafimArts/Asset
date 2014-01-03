<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:28
 * @copyright 2008-2013 RuDev
 * @package autoload.php
 * @since 1.0
 */
spl_autoload_register(function($file){
    $file = __DIR__ . '/../' . str_replace('\\', '/', $file) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});