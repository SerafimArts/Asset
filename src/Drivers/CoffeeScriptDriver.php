<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 15:58
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Drivers;

use SplFileObject;
use CoffeeScript\Error;
use CoffeeScript\Compiler;
use CoffeeScript\SyntaxError;
use Serafim\Asset\Compiler\CompilerException;

/**
 * Class CoffeeScriptDriver
 * @package Serafim\Asset\Drivers
 */
class CoffeeScriptDriver implements DriverInterface
{
    /**
     * @param SplFileObject $file
     * @param string $sources
     * @return string
     * @throws CompilerException
     */
    public function build(SplFileObject $file, $sources)
    {
        try {
            $result = Compiler::compile($sources, [
                'filename' => $file->getFilename(),
                'header'   => false
            ]);

        } catch (SyntaxError $e) {
            throw $this->getException($file, $e);

        } catch (Error $e) {
            throw $this->getException($file, $e);
        }

        return $result;
    }

    /**
     * @param SplFileObject $file
     * @param Error $e
     * @return CompilerException
     */
    protected function getException(SplFileObject $file, Error $e)
    {
        if ($file->getSize() === 0) {
            $message = sprintf('file %s is empty', $file->getPathname());
            return (new CompilerException($message))
                ->setFile($file)
                ->setLine(1);
        }

        $line = $e->getLine();
        if (preg_match('/[a-z]+:([0-9]+)/', $e->getMessage(), $matches)) {
            $line = $matches[0];
        }

        $message = $e->getMessage();
        $message = preg_replace('/(In\s.*?,\s)/', '', str_replace('YY_', '', $message));

        return (new CompilerException($message))
            ->setFile($file)
            ->setLine($line);
    }
}
