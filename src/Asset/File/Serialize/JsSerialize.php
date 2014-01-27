<?php
namespace Asset\File\Serialize;

use Asset\File\Serialize\AbstractSerialize;

class JsSerialize extends AbstractSerialize
{
    final public function toSource()
    {
        return '<script>' . $this->source . '</script>';
    }

    final public function toLink()
    {
        return ' <script src="' . $this->cache() . '"></script>';
    }
}