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
use Serafim\Asset\Serialize\AbstractSerialize;

class CssSerialize extends AbstractSerialize
{
    public function toLink($args = [])
    {
        return $this->createTag('link', array_merge($args, [
            'href' => $this->getFile()->getPublicUrl(),
            'rel' => 'stylesheet',
            'type' => 'text/css'
        ]));
    }

    public function toInline($args = [])
    {
        return $this->createTag('style', $args, $this->getSources());
    }
}