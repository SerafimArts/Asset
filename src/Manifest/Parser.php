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

use Serafim\Asset\Exception\ModifierException;
use Symfony\Component\Finder\Finder;
use SplFileInfo;
use Serafim\Asset\Compiler\File;

/**
 * Class Parser
 * @package Serafim\Asset\Manifest
 */
class Parser
{
    protected $rules = [
        '\s*\/[\/|\*]\s*=\s*require\s*(?:\((.*?)\))?\s+(.*?)?\n'
    ];

    protected $sources;

    public function __construct(File $file, $sources, $app)
    {
        $pattern = '#' . $this->rules[0] . '#iu';

        $output = $sources;

        $this->parse($file, $pattern, $sources, function($finder, $srcLine, $dataLine) use (&$output, $file, $app) {
            $included = "\n";
            foreach ($finder as $f) {
                $include     = new File($f, $file->getConfigs());
                $expression  = trim($dataLine);
                $included   .=
                    $include->getFileHeader($expression) .
                    $include->compile($app) . "\n";
            }
            $output = str_replace($srcLine, $included, $output);
        });

        $this->sources = $output;

    }

    protected function parse(File $file, $pattern, $sources, callable $after)
    {
        preg_match_all($pattern, $sources, $matches);

        for ($i=0; $i < count($matches[2]); $i++) {
            /**
             * $matches[0] line
             * $matches[1] modifiers
             * $matches[2] expression
             */

            $finder = $this->search(
                trim($file->getSplFileInfo()->getPath() . '/' . $matches[2][$i]),
                $matches[1][$i]
            );

            $after($finder, $matches[0][$i], $matches[2][$i]);
        }
    }

    protected function search($path, $mods = null)
    {
        $finder = new Finder();
        $modifiers = [];


        // some/any/*.extension
        preg_match_all('#\*\.([a-z]+)?$#u', $path, $matches);
        if (count($matches[1]) && $matches[1][0]) {
            $path = str_replace($matches[0][0], '*', $path);
            $finder->name('*' . $matches[1][0]);
        }

        // some/any/filename.extension
        if (substr($path, mb_strlen($path) - 1) != '*') {
            return [new SplFileInfo($path)];
        }

        // require([modifier:value])
        if ($mods) {
            foreach (explode(',', $mods) as $mod) {
                $parts = explode(':', $mod);
                if (count($parts) != 2) {
                    throw new ModifierException('Unknown modifier format ' . $mod);
                }
                $modifiers[] = $this->parseModifier(
                    trim($parts[0]),
                    trim($parts[1])
                );
            }
        }

        // some/any/*
        $finder
            ->files()
            ->in(substr($path, 0, mb_strlen($path) - 1))
            // all subdirectories first
            ->sort(function(SplFileInfo $f1, SplFileInfo $f2){
                $f1c = substr_count(str_replace('\\', '/', $f1->getRelativePathname()), '/');
                $f2c = substr_count(str_replace('\\', '/', $f2->getRelativePathname()), '/');
                if ($f1c > $f2c) {
                    return -1;
                } else if ($f1c < $f2c) {
                    return 1;
                }
                return 0;
            })
            ->sortByName();
        foreach ($modifiers as $mod) {
            $mod($finder);
        }
        return $finder;
    }

    public function getSources()
    {
        return $this->sources;
    }

    public function parseModifier($method, $arg)
    {
        switch ($method) {
            case 'sort':
            case 'rsort':
                return function (Finder $result) use($method, $arg) {
                    switch ($arg) {
                        case 'name':
                            $result->sortByName();
                            break;
                        case 'type':
                            $result->sortByType();
                            break;
                        case 'time':
                            $result->sortByModifiedTime();
                            break;
                        default:
                            throw new ModifierException('Unknown modifier argument ' . $arg);
                    }
                    if ($method == 'rsort') {
                        $result->sort(function (SplFileInfo $a, SplFileInfo $b) {
                            return strcmp($b->getRealpath(), $a->getRealpath());
                        });
                    }
                };
            case 'name':
                return function (Finder $result) use($method, $arg) {
                    $result->name($arg);
                };
            case 'not':
                return function (Finder $result) use($method, $arg) {
                    $result->notName($arg);
                };
            case 'size':
                return function (Finder $result) use($method, $arg) {
                    $result->size($arg);
                };
            case 'depth':
                return function (Finder $result) use($method, $arg) {
                    $result->depth($arg);
                };
            case 'exclude':
                return function (Finder $result) use($method, $arg) {
                    $result->exclude($arg);
                };
            default:
                throw new ModifierException('Undefined modifier ' . $method);
        }
    }
}
