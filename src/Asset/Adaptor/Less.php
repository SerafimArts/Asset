<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Serafim
 * Date: 06.08.13 13:13
 * Package: Asset Scss.php 
 */
namespace Asset\Adaptor;

/**
 * Class Less
 * @package Asset\Adaptor
 */
class Less extends AbstractAdaptor
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
        $sources = \Asset\Trigger::call('less', $sources);
        $less = new \lessc;
        return $less->compile($sources);
    }
}