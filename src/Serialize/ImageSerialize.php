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
 * Class ImageSerialize
 * @package Serafim\Asset\Serialize
 */
class ImageSerialize extends AbstractSerialize
{
    /**
     * @param array $args
     * @return mixed|string
     */
    public function toLink($args = [])
    {
        return $this->createTag('img', array_merge($args, [
            'src' => $this->getFile()->getPublicUrl(),
            'alt' => $this->getFile()->getPublicUrl()
        ]), '');
    }

    /**
     * @param array $args
     * @return mixed|string
     */
    public function toInline($args = [])
    {
        return $this->createTag('img', array_merge($args, [
            'src' => 'data:image/' . $this->getFile()->getSplFileInfo()->getExtension() .
                ';base64,' . base64_encode(file_get_contents($this->getFile()->getPublicPath())),
            'alt' => $this->getFile()->getPublicUrl()
        ]), '');
    }
}
