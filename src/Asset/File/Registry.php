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
use \Asset\Finder;

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
     * Files hash
     * @var array
     */
    private static $_existsFiles = [];

    /**
     * @param Config $config
     * @param $file
     */
    public static function push(Config $config, $file)
    {
        if (!realpath($file) && $config->get(Config::BASE_PATH)) {
            $file = $config->get(Config::BASE_PATH) . DIRECTORY_SEPARATOR . $file;
        }
        $finder = new Finder($file);
        $files  = $finder->search();
        foreach ($files as $f) {
            self::_push($f);
        }
    }

    /**
     * @param $file
     */
    private static function _push($file)
    {
        $ext = Virtual::ext($file);
        $adaptor = '\\Asset\\Adaptor\\' . ucfirst($ext);

        if (!in_array(md5($file), self::$_existsFiles)) {
            self::$_existsFiles[] = md5($file);
            self::$_files[$adaptor::type()][] = new Virtual($file, $ext);
        }
    }


    /**
     * @param Config $config
     * @param null $postfix
     * @return string
     */
    public static function compile(Config $config, $postfix = null)
    {
        $result = '';
        $cache  = new Cache($config);
        foreach (self::$_files as $files) {
            if ($cache->compare($files)) {
                if ($postfix) { $cache->setPostfix($postfix); }
                $cache->set($files);
            }
            $result .= $files[0]->serialize($cache->url($files)) . "\n";
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function flush()
    {
        return self::$_files = [];
    }
}
