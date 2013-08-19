<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 09.08.13 5:19
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset\Adaptor;


/**
 * Class AbstractAdaptor
 * @package Asset\Adaptor
 */
abstract class AbstractAdaptor
{
    const TYPE_STYLE    = 'css';
    const TYPE_SCRIPT   = 'js';
    const TYPE_OTHER    = 'other';

    /**
     * @var string
     */
    protected static $type = self::TYPE_OTHER;

    /**
     * @var array
     */
    private static $_extensions = [
        self::TYPE_STYLE    => '.css',
        self::TYPE_SCRIPT   => '.js',
        self::TYPE_OTHER    => '.txt'
    ];

    /**
     * @var array
     */
    private static $_serialize = [
        self::TYPE_STYLE    => '<link rel="stylesheet" href="%s" />',
        self::TYPE_SCRIPT   => '<script src="%s"></script>',
        self::TYPE_OTHER    => '<script>%s</script>'
    ];

    /**
     * @return string
     */
    public static function type()
    {
        return static::$type;
    }

    /**
     * @param $path
     * @return string
     */
    public static function serialize($path)
    {
        switch (static::$type) {
            case self::TYPE_STYLE:
            case self::TYPE_SCRIPT:
                return sprintf(
                    self::$_serialize[static::$type],
                    $path
                );
            default:
                $class = get_class();
                return sprintf(
                    self::$_serialize[self::TYPE_OTHER],
                    "alert('Undeclared adaptor type ${class}');"
                );
        }
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return self::$_extensions[self::$type];
    }

    /**
     * @param $sources
     * @return mixed
     */
    abstract public static function compile($sources);
}