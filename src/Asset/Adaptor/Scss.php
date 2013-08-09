<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Serafim
 * Date: 06.08.13 13:13
 * Package: Asset Scss.php 
 */
namespace Asset\Adaptor;

/**
 * Class Scss
 * @package Asset\Adaptor
 */
class Scss extends AbstractAdaptor
{
    /**
     * @var
     */
    protected static $type = self::TYPE_STYLE;

    /**
     * @param $sources
     * @return mixed
     */
    public static function compile($sources)
    {
        $sources = \Asset\Trigger::call('coffee', $sources);
        $scss = new \scssc;
        return $scss->compile($sources);
    }
}