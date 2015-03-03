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

/**
 * Class JsSerialize
 * @package Serafim\Asset\Serialize
 */
class JsSerialize extends AbstractSerialize
{
    /**
     * @param array $args
     * @return mixed|string
     */
    public function toLink($args = [])
    {
        return $this->createTag('script', array_merge($args, [
            'src'  => $this->getFile()->getPublicUrl(),
            'type' => 'text/javascript'
        ]), '');
    }

    /**
     * @param array $args
     * @return mixed|string
     */
    public function toInline($args = [])
    {
        return $this->createTag('script', $args, $this->getSources());
    }
}
