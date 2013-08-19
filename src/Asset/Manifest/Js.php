<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 20.08.13 0:49
 * @copyright 2008-2013 RuDev
 * @since 2.0.0
 */
namespace Asset\Manifest;

/**
 * Js manifest parser
 * @package Asset\Manifest
 */
class Js implements ManifestInterface
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
        $p = '#^[//|/\*|\*]=\s*require\s*(.*?)$#misu';
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