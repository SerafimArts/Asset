<?php
namespace Asset\Driver;

use lessc as LessCompiler;

class LessDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_CSS;

    public function make()
    {
        $less = new LessCompiler();
        $this->result = $less->compile($this->source);
    }
}