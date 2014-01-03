<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 10.08.13 1:56
 * @copyright 2008-2013 RuDev
 * @since 1.1.1
 */
namespace Asset;

/**
 * Class Trigger
 * @package Asset
 */
class Trigger
{
    /**
     * @var array
     */
    private static $_triggers = [];

    /**
     * @param callable $cb
     */
    public static function css(callable $cb)
    {
        self::adaptor('css', $cb);
    }

    /**
     * @param callable $cb
     */
    public static function js(callable $cb)
    {
        self::adaptor('js', $cb);
    }

    /**
     * @param $name
     * @param callable $cb
     */
    public static function adaptor($name, callable $cb)
    {
        if (!isset(self::$_triggers[$name])) { self::$_triggers[$name] = []; }
        self::$_triggers[$name][] = $cb;
    }

    /**
     * @param $name
     * @param $data
     * @return mixed
     */
    public static function call($name, $data)
    {
        if (!isset(self::$_triggers[$name])) {
            return $data;
        }
        foreach (self::$_triggers[$name] as $trigger) {
            $data = $trigger($data);
        }
        return $data;
    }
}