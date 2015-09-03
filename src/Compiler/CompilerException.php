<?php
namespace Serafim\Asset\Compiler;

use RuntimeException;

/**
 * Class CompilerException
 * @package Serafim\Asset\Compiler
 */
class CompilerException extends \RuntimeException
{
    /**
     * @param $path
     * @return $this
     */
    public function setFile($path)
    {
        $this->file = $path;
        return $this;
    }

    /**
     * @param $line
     * @return $this
     */
    public function setLine($line)
    {
        $this->line = $line;
        return $this;
    }
}
