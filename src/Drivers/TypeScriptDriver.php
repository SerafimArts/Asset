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

use Exception;
use SplFileObject;
use Symfony\Component\Process\Process;
use Serafim\Asset\Compiler\CompilerException;

/**
 * Class TypeScriptDriver
 * @package Serafim\Asset\Drivers
 */
class TypeScriptDriver implements DriverInterface
{
    /**
     * @param SplFileObject $file
     * @param string $sources
     * @return string
     * @throws Exception
     */
    public function build(SplFileObject $file, $sources)
    {
        return $this->run($file, $sources);
    }

    /**
     * @param SplFileObject $file
     * @param $sources
     * @return string
     * @throws Exception
     */
    protected function run(SplFileObject $file, $sources)
    {
        $sourceFileName     = substr(md5($file->getPathname() . mt_rand(0, 9999)), 0, 6) . '.tmp';
        $sourceFile         = str_replace('\\', '/', sys_get_temp_dir()) . '/' . $sourceFileName . '.ts';
        $destinationFile    = str_replace('\\', '/', sys_get_temp_dir()) . '/' . $sourceFileName . '.ts.js';

        file_put_contents($sourceFile, $sources);

        $process = new Process(sprintf('tsc "%s" --out "%s"', $sourceFile, $destinationFile));
        $process->setTimeout(1000);
        $process->run();


        if (!$process->isSuccessful()) {
            throw $this->getException(
                $file,
                $process->getErrorOutput(),
                $process->getExitCode(),
                $sourceFile
            );
        }

        $result = file_get_contents($destinationFile);

        return $result;
    }

    /**
     * @param SplFileObject $file
     * @param string $errorMessage
     * @param int $code
     * @param string $virtualFile
     * @return CompilerException
     */
    protected function getException(SplFileObject $file, $errorMessage, $code, $virtualFile)
    {
        $errorMessage = str_replace(
            [
                "\r",
                str_replace('\\', '/', $virtualFile)
            ],
            [
                '',
                str_replace('\\', '/', $file->getPathname())
            ],
            trim(str_replace('\\', '/', $errorMessage))
        );


        $line = 0;
        if (preg_match('/\((\d+),.*?\)/', $errorMessage, $matches)) {
            $line = ((int)$matches[1]) - 1;
        }

        return CompilerException::fromArray(explode("\n", $errorMessage), $code)
            ->setLine($line)
            ->setFile($file);
    }
}
