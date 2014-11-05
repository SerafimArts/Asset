<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (15.10.2014 23:11)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Serialize;

use Serafim\Asset\Compiler\File;

/**
 * Class AbstractSerialize
 * @package Serafim\Asset\Serialize
 */
abstract class AbstractSerialize
{
    protected $sources;
    protected $url;
    protected $file;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
        $this->url  = $file->getAssetPath()->url;
        $this->sources = $file->build();
    }

    public function getSources()
    {
        return $this->sources;
    }

    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param array $options
     * @return mixed
     */
    abstract public function getInline(array $options = []);

    /**
     * @param array $options
     * @return mixed
     */
    abstract public function toLink(array $options = []);



    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->toLink();
    }

    /**
     * @param $name
     * @param array $options
     * @param null $content
     * @return string
     */
    protected function createTag($name, array $options = [], $content = null)
    {
        if ($content === null) {
            return '<' . $name . ' ' . $this->parseArgs($options) . ' />';
        }
        return '<' . $name . ' ' . $this->parseArgs($options) . '>' .
            $content . '</' . $name . '>';
    }

    /**
     * @param $args
     * @return string
     */
    protected function parseArgs($args)
    {
        $result = [];
        foreach ($args as $attr => $val) {
            $result[] = $attr . '="' . $val . '"';
        }
        return implode(' ', $result);
    }
}
