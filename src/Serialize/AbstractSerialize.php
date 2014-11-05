<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (05.11.2014 15:27)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 
namespace Serafim\Asset\Serialize;

use Serafim\Asset\Compiler\File;

abstract class AbstractSerialize
{
    protected $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    abstract public function toLink($args = []);

    abstract public function toInline($args = []);

    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getSources()
    {
        return file_get_contents($this->file->getPublicPath());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->toLink();
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
            return '<' . $name . $this->parseArgs($options) . ' />';
        }
        return '<' . $name . $this->parseArgs($options) . '>' .
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
        return (
            count($result)
                ? ' ' . implode(' ', $result)
                : ''
        );
    }
}
