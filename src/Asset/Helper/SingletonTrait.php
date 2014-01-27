<?php
namespace Asset\Helper;

use ReflectionClass;

trait SingletonTrait
{
    private static $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            call_user_func_array([__CLASS__, 'setInstance'], func_get_args());
        }
        return self::$_instance;
    }

    public static function setInstance()
    {
        $reflection = new ReflectionClass(__CLASS__);
        self::$_instance = $reflection->newInstanceArgs(
            func_get_args()
        );
    }
}