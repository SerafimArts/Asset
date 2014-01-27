<?php
namespace Asset\File\Serialize;

use Asset\File\Serialize\AbstractSerialize;

class CssSerialize extends AbstractSerialize
{
    final public function toSource()
    {
        return '<style>' . $this->source . '</style>';
    }

    final public function toLink()
    {
        return ' <link href="' . $this->cache() . '" rel="stylesheet" type="text/css" />';
    }
}