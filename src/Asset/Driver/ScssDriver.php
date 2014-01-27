<?php
namespace Asset\Driver;

use scssc as ScssCompiler;

class ScssDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_CSS;

    public function make()
    {
        $scss = new ScssCompiler();
        $scss->setImportPaths(
            $this->file->getPath()
        );
        $this->result = $scss->compile($this->source);
    }
}