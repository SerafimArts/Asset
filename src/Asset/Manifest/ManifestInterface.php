<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 20.08.13 0:48
 * @copyright 2008-2013 RuDev
 * @since 2.0.0
 */
namespace Asset\Manifest;

/**
 * Class ManifestInterface
 * @package Asset\Manifest
 */
interface ManifestInterface
{
    /**
     * @param $source
     */
    public function __construct($source);

    /**
     * @return mixed
     */
    public function getRequires();
}