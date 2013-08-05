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
     * @var mixed
     */
    private $_type;

    /**
     * @var
     */
    private $_file;

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
        if (!file_exists($file)) {
            throw new FileNotFoundException("File ${file} not found.");
        }
        $this->_file    = $file;
        $this->_config  = $config === null ? new Config : $config;
        $this->_type    = $this->_getType($file);
    }

    /**
     * @param $type
     * @return $this
     */
    public function asType($type)
    {
        $this->_type = $type;
        return $this;
    }

    /**
     * @param $ext
     * @return bool|string
     */
    public function compile($ext)
    {
        $adaptor    = ('\\Asset\\Adaptor\\' . ucfirst($this->_type));
        return (new Cache($this->_config, $ext))
            ->check($this->_file, function() use ($adaptor) {
                    $compiler   = new $adaptor(
                        $this->_getSources($this->_file)
                    );
                    return $compiler->getResult();
                });
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