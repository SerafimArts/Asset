<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Serafim
 * Date: 05.08.13 23:14
 * Package: Asset Js.php 
 */
namespace Asset\Adaptor;

/**
 * Class Js
 * @package Asset\Adaptor
 */
class Js extends AbstractAdaptor
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
        return $sources;
    }
}