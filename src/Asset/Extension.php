<?php
namespace Asset;

class Extension
{
    private static $_aliases = [
        'scss'      => '\\Asset\\Driver\\ScssDriver',
        'coffee'    => '\\Asset\\Driver\\CoffeeDriver',
        'less'      => '\\Asset\\Driver\\LessDriver',
        'css'       => '\\Asset\\Driver\\CssDriver',
        'js'        => '\\Asset\\Driver\\JsDriver',
        'pjs'       => '\\Asset\\Driver\\PjsDriver',
    ];

    public static function append($ext, $driver)
    {
        if (!class_exists($driver)) {
            throw new \Exception('Could not find driver class ' . $driver);
        }
        self::$_aliases[$ext] = $driver;
    }

    public static function get($name) { return self::find($name); }
    public static function find($name)
    {
        if (isset(self::$_aliases[$name])) {
            return self::$_aliases[$name];
        }
        return null;
    }

    public static function getAll()
    {
        return self::$_aliases;
    }
}