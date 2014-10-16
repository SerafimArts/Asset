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
 * Class JsSerialize
 * @package Serafim\Asset\Serialize
 */
class JsSerialize
    extends AbstractSerialize
{
    /**
     * @var string
     */
    protected $extension = '.js';

    /**
     * @param array $options
     * @return mixed|string
     */
    public function getInline(array $options = [])
    {
        return $this->createTag('script', $options, $this->content);
    }

    /**
     * @param array $options
     * @return mixed|string
     */
    public function toLink(array $options = [])
    {
        return $this->createTag('script', array_merge($options, [
            'src'  => $this->url,
            'type'  => 'text/javascript'
        ]), '');
    }
}
