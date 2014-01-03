<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 20.08.13 1:13
 * @copyright 2008-2013 RuDev
 * @since 2.0.0
 */
namespace Asset\Manifest;

/**
 * Css manifest parser
 * @package Asset\Manifest
 */
class Css implements ManifestInterface
{
    /**
     * @var array
     */
    private $_requires = [];

    /**
     * @param $source
     */
    public function __construct($source)
    {
        $p = '#^/?\*=\s*require\s*(.*?)$#misu';
        preg_match_all($p, $source, $m);
        $this->_requires = $m[1];
    }

    /**
     * @return array
     */
    public function getRequires()
    {
        return $this->_requires;
    }
}