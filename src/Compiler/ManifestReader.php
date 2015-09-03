<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 18:54
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Compiler;

use LogicException;
use InvalidArgumentException;

/**
 * Class ManifestReader
 * @package Serafim\Asset\Compiler
 */
class ManifestReader
{
    /**
     * @var File
     */
    protected $file;

    /**
     * Pattern for pre-compiled files
     *
     * @var string
     */
    protected $compilePattern = '(\s*)[/#]+\s*=\s*(require\s*(.*))';

    /**
     * Pattern for require-first files
     *
     * @var string
     */
    protected $requirePattern = '(\s*)[/#]+\s*=\s*(source(?:s)?\s*(.*))';

    /**
     * @var string
     */
    protected $sources;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file    = $file;
        $this->sources = str_replace("\r", '', $this->file->getSourceCode());
    }

    /**
     * @return string
     */
    public function getCompilePattern()
    {
        return $this->compilePattern;
    }

    /**
     * @param string $pattern
     */
    public function setCompilePattern($pattern)
    {
        $this->compilePattern = $pattern;
    }

    /**
     * @return string
     */
    public function getRequirePattern()
    {
        return $this->compilePattern;
    }

    /**
     * @param string $pattern
     */
    public function setRequirePattern($pattern)
    {
        $this->compilePattern = $pattern;
    }

    /**
     * @return \Generator
     * @throws LogicException
     */
    public function getSourceDependencies()
    {
        return $this->getDependencies($this->requirePattern);
    }

    /**
     * @return \Generator
     * @throws LogicException
     */
    public function getCompiledDependencies()
    {
        return $this->getDependencies($this->compilePattern);
    }

    /**
     * @return \Generator
     * @throws LogicException
     */
    protected function getDependencies($pattern)
    {
        $pattern = sprintf('/%s/', str_replace('/', '\/', $pattern));
        preg_match_all($pattern, $this->sources, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);

        foreach ($matches as $id => $match) {
            yield trim($match[0][0]) => $this->parseQuery($this->sources, $match);
        }
    }

    /**
     * @param $sources
     * @param $matches
     * @return File[]
     * @throws LogicException
     * @throws InvalidArgumentException
     */
    protected function parseQuery($sources, $matches)
    {
        // Format matches
        list($fullQuery, $whitespaces, $requireQuery, $query) = $matches;
        list($fullQuery, $whitespaces, $requireQuery, $char, $query) =
            [$fullQuery[0], $whitespaces[0], $requireQuery[0], $query[1], $query[0]];


        $collection = (new FileCollection($query, $this->file->getInputPaths()))
            ->attachDriverStorage($this->file->getDriverStorage());


        // Check dependencies
        if (!count($collection->getFiles())) {
            $line    = count(explode("\n", substr($sources, 0, $char)));
            $message = sprintf('Query "%s" has no found any files.', trim($requireQuery));

            throw (new CompilerException($message))
                ->setFile($this->file->getSplFileObject()->getPathname())
                ->setLine($line);
        }


        return $collection
            ->each(function(File $file) use ($whitespaces) {
                $file->setWhitespaceLevel(substr_count($whitespaces, ' '));
            })
            ->getFiles();
    }

}
