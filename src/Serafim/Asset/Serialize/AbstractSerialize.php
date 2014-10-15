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

use Symfony\Component\Finder\SplFileInfo;

/**
 * Class AbstractSerialize
 * @package Serafim\Asset\Serialize
 */
abstract class AbstractSerialize
{
    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var string
     */
    protected $extension = 'txt';

    /**
     * @param SplFileInfo $file
     */
    public function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    /**
     * @param $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
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
     * @return string
     */
    public function getSources()
    {
        return $this->content;
    }

    /**
     * @return SplFileInfo
     */
    public function getFileInfo()
    {
        return $this->file;
    }

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
