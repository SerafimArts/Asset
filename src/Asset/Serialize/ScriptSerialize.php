<?php namespace Asset\Serialize;

    /**
     * This file is part of Asset package.
     *
     * Serafim <nesk@xakep.ru> (03.06.2014 14:27)
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

/**
 * Class ScriptSerialize
 * @package Asset\Serialize
 */
class ScriptSerialize extends AbstractSerialize
{
    /**
     * @param array $args
     * @return mixed|string
     */
    public function getLink($args = [])
    {
        return $this->tag('script', '',
            array_merge(['src' => $this->getSourcesPath()], $args)
        );
    }

    /**
     * @param array $args
     * @return mixed|string
     */
    public function getInline($args = [])
    {
        return $this->tag('script', $this->getSources(), $args);
    }
}
