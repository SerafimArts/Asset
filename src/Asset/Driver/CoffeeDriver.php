<?php
namespace Asset\Driver;

use CoffeeScript\Compiler as CSCompiler;

class CoffeeDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_JS;

    public function make()
    {
        $this->result = $this->cache($this->source, function(){
            return CSCompiler::compile(
                $this->source, ['filename' => $this->file->getFilename()]
            );
        });
    }
}