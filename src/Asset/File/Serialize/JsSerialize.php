<?php
namespace Asset\File\Serialize;

use Asset\File\Serialize\AbstractSerialize;

class JsSerialize extends AbstractSerialize
{
    final public function toSource($args = [])
    {
        return '<script ' . $this->argsToAttrs($args) . '>' . $this->source . '</script>';
    }

    final public function toLink($args = [])
    {
        return ' <script ' . $this->argsToAttrs($args) . ' src="' . $this->cache() . '"></script>';
    }
}