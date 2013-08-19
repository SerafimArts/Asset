<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 20.08.13 0:51
 * @copyright 2008-2013 RuDev
 * @since 2.0.0
 */
namespace Asset;

use \Asset\File\Virtual;
use \Asset\Exception\FileNotFoundException;
use \Asset\Config;

/**
 * Class Manifest
 * @package Asset
 */
class Manifest
{
    /**
     * @var
     */
    private $_parser;


    /**
     * @param Config $config
     * @param $file
     * @param Compiler $compiler
     * @throws FileNotFoundException
     */
    public function __construct(Config $config, $file, Compiler $compiler)
    {
        if (!realpath($file) && $config->get(Config::BASE_PATH)) {
            $file = $config->get(Config::BASE_PATH) . DIRECTORY_SEPARATOR . $file;
        }
        if (!file_exists($file)) {
            throw new FileNotFoundException("Source file ${file} could'n be find.");
        }

        $ext    = Virtual::ext($file);
        $parser = '\\Asset\\Manifest\\' . ucfirst($ext);
        $this->_parser = new $parser(file_get_contents($file));
    }

    /**
     * @return mixed
     */
    public function compile()
    {
        return $this->_parser->getRequires();
    }
}