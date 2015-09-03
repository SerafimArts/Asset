<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 16:31
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Compiler;

use Exception;
use RuntimeException;

/**
 * Class CompilerException
 * @package Serafim\Asset\Compiler
 */
class CompilerException extends RuntimeException
{
    /**
     * @param array $exceptionMessages
     * @param int $code
     * @return CompilerException
     */
    public static function fromArray(array $exceptionMessages, $code = 0)
    {
        $lastException = null;

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        foreach ($exceptionMessages as $message) {
            if (!trim($message)) {
                continue;
            }

            $lastException = ($lastException === null)
                ? new static($message, $code, null, $trace)
                : new static($message, $code, $lastException, $trace);
        }

        return $lastException;
    }

    /**
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     * @param array $trace
     */
    public function __construct($message = '', $code = 0, Exception $previous = null, array $trace = [])
    {
        $prefix = '';
        if ($trace === []) {
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        }
        if (isset($trace[1]['class'])) {
            $prefix = sprintf(
                '[%s Error]: ',
                basename($trace[1]['class'])
            );
        }

        parent::__construct($prefix . ucfirst($message), $code, $previous);
    }

    /**
     * @param $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
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
