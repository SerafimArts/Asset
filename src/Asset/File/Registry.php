<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 09.08.13 5:15
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset\File;

use \Asset\Config;
use \Asset\Cache;

/**
 * Class Registry
 * @package Asset\File
 */
class Registry
{
    /**
     * @var array
     */
    private static $_files = [];

    /**
     * @param $file
     */
    public static function push($file)
    {
        $ext = self::_getExt($file);
        $adaptor = '\\Asset\\Adaptor\\' . ucfirst($ext);
        self::$_files[$adaptor::type()][] = new Virtual($file, $ext);
    }

    /**
     * @param $name
     * @return mixed
     */
    private static function _getExt($name)
    {
        $name = explode('.', $name);
        return end($name);
    }

    /**
     * @param Config $config
     * @return string
     */
    public static function compile(Config $config)
    {
        $result = '';
        $cache  = new Cache($config);
        foreach (self::$_files as $files) {
            if ($cache->compare($files)) {
                $cache->set($files);
            }
            $result .= $files[0]->serialize($cache->url($files)) . "\n";
        }
        return $result;
    }
    
    
    
    public static function flush()
    {
        self::$_files = [];
    }
}
