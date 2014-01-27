<?php
namespace Asset\Driver;

class JsDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_JS;

    public function make()
    {
        $this->result = $this->cache($this->source, function(){
            return $this->source;
        });
    }
}