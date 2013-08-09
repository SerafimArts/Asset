<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 09.08.13 5:18
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset;


/**
 * Class Cache
 * @package Asset
 */
class Cache
{
    /**
     * @var Config
     */
    private $_config;

    /**
     * @param Config $conf
     */
    public function __construct(Config $conf)
    {
        $this->_config = $conf;
        if (!is_dir($conf->get(Config::CACHE))) {
            mkdir($conf->get(Config::CACHE), 0777, true);
        }
    }

    /**
     * @param array $files
     */
    public function set(array $files)
    {
        $hash = $this->hash($files);
        $content = '';
        foreach ($files as $file) {
            $content .= $file->compile();
        }
        file_put_contents($hash, $content);
    }

    /**
     * @param array $files
     * @return bool
     */
    public function compare(array $files)
    {
        $hash = $this->hash($files);
        $compareTime = function() use ($hash, $files){
            foreach ($files as $file) {
                if (filemtime($file->path()) > filemtime($hash)) {
                    return true;
                }
            }
            return false;
        };
        return (
            !file_exists($hash) ||
            $compareTime()
        );
    }

    /**
     * @param array $files
     * @return string
     */
    public function hash(array $files)
    {
        $path = '';
        foreach ($files as $file) { $path .= $file->path(); }
        return $this->_config->get(Config::CACHE) . md5($path) . '.' . $files[0]->type();
    }

    /**
     * @param array $files
     * @return string
     */
    public function url(array $files)
    {
        $path = '';
        foreach ($files as $file) { $path .= $file->path(); }
        return $this->_config->get(Config::URL) . md5($path) . '.' . $files[0]->type();
    }
}