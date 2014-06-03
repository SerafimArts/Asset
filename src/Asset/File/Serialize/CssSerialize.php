<?php
namespace Asset\File\Serialize;

use Asset\File\Serialize\AbstractSerialize;

class CssSerialize extends AbstractSerialize
{
    final public function toSource($args = [])
    {
        return '<style ' . $this->argsToAttrs($args) . '>' . $this->source . '</style>';
    }

    final public function toLink($args = [])
    {
        return ' <link ' . $this->argsToAttrs($args) . ' href="' . $this->cache() . '" rel="stylesheet" type="text/css" />';
    }
}