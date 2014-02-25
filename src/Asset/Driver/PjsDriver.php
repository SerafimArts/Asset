<?php
namespace Asset\Driver;

class PjsDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_JS;


    public function make()
    {
        $this->result = $this->cache($this->source, function(){
            $pjs = new \PHPToJavascript\PHPToJavascript();
            $pjs->addFromString($this->source);
            return $pjs->toJavascript();
        });
    }


}