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
 * Class CssSerialize
 * @package Serafim\Asset\Serialize
 */
class CssSerialize
    extends AbstractSerialize
{
    /**
     * @var string
     */
    protected $extension = '.css';

    /**
     * @param array $options
     * @return mixed|string
     */
    public function getInline(array $options = [])
    {
        return $this->createTag('style', $options, $this->content);
    }

    /**
     * @param array $options
     * @return mixed|string
     */
    public function toLink(array $options = [])
    {
        return $this->createTag('link', array_merge($options, [
            'href'  => $this->url,
            'rel'   => 'stylesheet',
            'type'  => 'text/css'
        ]));
    }
}
