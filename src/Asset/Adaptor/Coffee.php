<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Serafim
 * Date: 05.08.13 22:35
 * Package: Asset Coffee.php 
 */
namespace Asset\Adaptor;

/**
 * Class Coffee
 * @package Asset\Adaptor
 */
class Coffee extends AbstractAdaptor
{
    /**
     * @var
     */
    protected static $type = self::TYPE_SCRIPT;

    /**
     * @param $sources
     * @return mixed
     */
    public static function compile($sources)
    {
        return parent::trigger(function($data){
            return \CoffeeScript\Compiler::compile($data);
        }, $sources);
    }
}