<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (14.10.2014 19:53)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 
namespace Serafim\Asset\Manifest;


use Illuminate\Support\Facades\Cache;
use Serafim\Asset\Compiler;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class Parser
 * @package Serafim\Asset\Manifest
 */
class Parser
{
    /**
     * @var array
     */
    protected $uniqueFiles = [];

    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @var array
     */
    protected $drivers = [];

    /**
     * @var array
     */
    protected $rules = [
        '\\Serafim\\Asset\\Manifest\\Rule\\MultipleRequireRule',
        '\\Serafim\\Asset\\Manifest\\Rule\\RequireRule',
    ];

    /**
     * @param Compiler $compiler
     * @param SplFileInfo $file
     */
    public function __construct(Compiler $compiler, SplFileInfo $file)
    {
        $this->file     = $file;
        $this->compiler = $compiler;
    }

    /**
     * @return Compiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param $className
     * @return mixed
     */
    public function addRule($className)
    {
        return $this->rules[] = $className;
    }

    /**
     * @return array
     */
    public function parse($driver)
    {
        $content  = $this->file->getContents();
        $lists    = $this->getFiles($driver, $content);

        foreach ($lists as $line => &$files) {
            $split = '';
            foreach ($files as $file) {
                $split .= $this->compiler->build(
                    $file,
                    $this->getDriver($file->getFilename())
                );
            }
            $content = str_replace($line, $split, $content);
        }

        return $this->compiler->build(
            $this->file,
            $this->getDriver($this->file->getFilename()),
            $content
        );

    }

    /**
     * @param $fileName
     * @return mixed
     */
    protected function getDriver($fileName)
    {
        $driver = $this->compiler->getDriver($fileName);
        if (!isset($this->drivers[$driver])) {
            $driver::check();
            $this->drivers[$driver] = new $driver($this->compiler);
        }
        return $this->drivers[$driver];
    }

    /**
     * @param $driver
     * @param $content
     * @return array
     */
    protected function getFiles($driver, $content)
    {
        $rules    = [];

        $patterns = $driver::getManifestPatterns();
        foreach ($patterns as $pattern) {
            preg_match_all('#' . str_replace('#', '\#', $pattern) . '\s#u', $content, $m);
            for ($i=0; $i<count($m[1]); $i++) {

                foreach ($this->rules as $rule) {
                    if ($rule::match($m[1][$i])) {
                        $rule = (new $rule($this->file, $m[1][$i], $this->uniqueFiles));
                        $rules[$m[0][$i]] = $rule->getFiles();

                        if ($this->compiler->getConfig()['unique']) {
                            $this->uniqueFiles  = array_merge(
                                $this->uniqueFiles,
                                $rule->getRelatives()
                            );
                        }
                        break;
                    }
                }
            }
        }


        return $rules;
    }
}
