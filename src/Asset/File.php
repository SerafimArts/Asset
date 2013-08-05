<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:07
 * @copyright 2008-2013 RuDev
 * @package File.php
 * @since 1.0
 */
namespace Asset;

use \Asset\Exception\FileNotFoundException;
use \Asset\Cache;

/**
 * Class File
 * @package Asset
 */
class File
{
    /**
     * @var array
     */
    private $_type = [];

    /**
     * @var array
     */
    private $_file = [];

    /**
     * @var Config
     */
    private $_config;

    /**
     * @param        $file
     * @param Config $config
     * @throws FileNotFoundException
     */
    public function __construct($file, Config $config = null)
    {
        $this->_config  = $config === null ? new Config : $config;

        if (is_array($file)) {
            foreach ($file as $f) {
                $this->_init($f, $config);
            }
        } else {
            $this->_init($file);
        }
    }

    /**
     * @param $file
     * @throws Exception\FileNotFoundException
     */
    private function _init($file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException("File ${file} not found.");
        }
        $this->_file[]  = $file;
        $this->_type[]  = $this->_getType($file);
    }


    /**
     * @param $type
     * @return $this
     */
    public function asType($type)
    {
        $this->_type = array_fill(0, count($this->_type),$type);
        return $this;
    }

    /**
     * @param $ext
     * @return bool|string
     */
    public function compile($ext)
    {
        $result = [];

        for ($i=0; $i<count($this->_file); $i++) {
            $adaptor    = ('\\Asset\\Adaptor\\' . ucfirst($this->_type[$i]));
            $result[] = (new Cache($this->_config, $ext))
                ->check($this->_file[$i], function() use ($i, $adaptor) {
                    $compiler   = new $adaptor(
                        $this->_getSources($this->_file[$i])
                    );
                    return $compiler->getResult();
                });
        }

        return count($result) > 1 ? $result : $result[0];
    }

    /**
     * @param $file
     * @return string
     */
    private function _getSources($file)
    {
        return file_get_contents($file);
    }

    /**
     * @param $filename
     * @return mixed
     */
    private function _getType($filename)
    {
        $f = explode('.', $filename);
        return end($f);
    }
}