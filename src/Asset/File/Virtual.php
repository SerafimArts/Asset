<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 09.08.13 5:15
 * @copyright 2008-2013 RuDev
 * @since 1.0
 */
namespace Asset\File;

use Asset\Exception\FileNotFoundException;

/**
 * Class Virtual
 * @package Asset\File
 */
class Virtual
{
    /**
     * @var string
     */
    private $_content;

    /**
     * @var string
     */
    private $_adaptor;

    /**
     * @var string
     */
    private $_path;

    /**
     * @var string
     */
    private $_type;

    /**
     * @param $file
     * @param $ext
     */
    public function __construct($file, $ext)
    {
        $this->_path    = realpath($file);
        if (!file_exists($this->_path)) {
            throw new FileNotFoundException("Source file ${file} could'n be find.");
        }
        $this->_content = file_get_contents($this->_path);
        $this->_adaptor = $adaptor = '\\Asset\\Adaptor\\' . ucfirst($ext);
        $this->_type    = $adaptor::type();
    }

    /**
     * @return mixed
     */
    public function compile()
    {
        $adaptor = $this->_adaptor;
        return $adaptor::compile($this->_content);
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->_path;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->_type;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function serialize($data)
    {
        $adaptor = $this->_adaptor;
        return $adaptor::serialize($data);
    }

    /**
     * @param $name
     * @return mixed
     */
    public static function ext($name)
    {
        $name = explode('.', $name);
        return end($name);
    }
}