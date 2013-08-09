<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 09.08.13 5:45
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset\Adaptor;

/**
 * Class Css
 * @package Asset\Adaptor
 */
class Css extends AbstractAdaptor
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
        return parent::trigger(function($data){
                return $data;
            }, $sources);
    }
}