<?php namespace Asset\Serialize;

    /**
     * This file is part of Asset package.
     *
     * serafim <nesk@xakep.ru> (03.06.2014 14:27)
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

/**
 * Class StyleSerialize
 * @package Asset\Serialize
 */
class StyleSerialize extends AbstractSerialize
{
    /**
     * @param array $args
     * @return mixed|string
     */
    public function getLink($args = [])
    {
        return $this->tag(
            'link',
            null,
            array_merge(
                [
                    'href' => $this->getSourcesPath(),
                    'rel' => 'stylesheet',
                    'type' => 'text/css'
                ],
                $args
            )
        );
    }

    /**
     * @param array $args
     * @return mixed|string
     */
    public function getInline($args = [])
    {
        return $this->tag('style', $this->getSources(), $args);
    }
}