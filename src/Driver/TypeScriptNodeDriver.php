<?php
/**
 * This file is part of Assets package.
 *
 * Serafim <nesk@xakep.ru> (03.09.2015 12:32)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Driver;

use RuntimeException;
use Serafim\Asset\Compiler\CompilerException;
use Symfony\Component\Process\Process;

/**
 * Class TypeScriptNodeDriver
 * @package Serafim\Asset\Driver
 */
class TypeScriptNodeDriver extends JsDriver
{
    /**
     * @var array
     */
    protected static $references = [];

    /**
     * @var array
     */
    protected static $excluded   = [];

    /**
     * @param $sources
     * @param $app
     * @return mixed
     */
    public function compile($sources, $app)
    {
        $precompiled = $this->getCachePath(
            $this->file->getRealPath() . '-precompile',
            '-' . basename($this->file->getRealPath())
        );

        $output      = $this->getCachePath($this->file->getRealPath());

        $result = $this->cache($app, function () use ($sources, $precompiled, $output) {
            // Add references
            copy($this->file->getRealPath(), $precompiled);
            $content = file_get_contents($precompiled);
            foreach (static::$references as $ref) {
                $content =
                    '/// <reference path="' . $ref . '" />' . "\n" .
                    $content;
            }
            file_put_contents($precompiled, $content);

            // Compile
            $command = sprintf('tsc "%s" --out "%s"', $precompiled, $output);
            $result  = $this->run($command, $output, $precompiled);
            $result  = str_replace(static::$excluded, '', $result);

            static::$excluded[] = $result;

            return $result;
        });

        static::$references[] = $this->getReferenceTo($precompiled);

        return $result;
    }

    /**
     * @param $value
     * @return string
     */
    protected function getCachePath($value, $postfix = '')
    {
        $hash = md5($value);
        $output = storage_path(sprintf(
            'framework/cache/%s/%s/%s',
            substr($hash, 0, 2),
            substr($hash, 2, 2),
            substr($hash, 4) . $postfix
        ));

        if (!is_dir(dirname($output))) {
            @mkdir(dirname($output), 0777, true);
        }

        return $output;
    }

    /**
     * @param $file
     * @return string
     */
    protected function getReferenceTo($file)
    {
        $path = str_replace('\\', '/', storage_path('framework/cache/'));
        $file = str_replace('\\', '/', $file);

        return '../../' . str_replace($path, '', $file);
    }

    /**
     * @param $command
     * @param $output
     * @param $virtualName
     * @return string
     */
    protected function run($command, $output, $virtualName)
    {
        $process = new Process($command);
        $process->setTimeout(1000);
        $process->run();

        if (!$process->isSuccessful()) {
            throw $this->createException(
                str_replace(
                    str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $virtualName),
                    str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $this->file->getPathname()),
                    $process->getErrorOutput()
                ),
                $process->getExitCode()
            );
        }

        return file_get_contents($output);
    }

    /**
     * @param $message
     * @param $code
     * @return CompilerException
     */
    protected function createException($message, $code)
    {
        $errors         = explode("\n", $message);
        $lastException  = null;

        foreach ($errors as $errorMessage) {
            if (!trim($errorMessage)) { continue; }

            $exception     = new CompilerException($errorMessage, $code, $lastException);
            $lastException = $exception;
        }

        $line = 0;
        if (preg_match('/\(([0-9]+).*?\)/', $exception->getMessage(), $matches)) {
            $line = (int)($matches[1] - 1);
        }

        return $lastException
            ->setLine($line)
            ->setFile($this->file->getRealPath());
    }

    /**
     * @return string
     */
    public function getOutputExtension()
    {
        return 'js';
    }
}
