<?php
namespace Asset\Driver;

class CssDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_CSS;

    public function make()
    {
        $this->result = $this->cache($this->source, function(){
            return $this->source;
        });
    }
}