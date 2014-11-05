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
use Serafim\Asset\Compiler\File;
use Symfony\Component\Finder\Finder;
use SplFileInfo;

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

    public function __construct(File $file, $sources)
    {
        $match = '#' . $this->rules[0] . '#iu';
        preg_match_all($match, $sources, $a);

        for ($i=0; $i<count($a[2]); $i++) {
            $finder = $this->search(
                dirname($file->getSplFileInfo()->getRealPath()) .
                    '/' . $a[2][$i],
                $a[1][$i]
            );


            $data = '';
            foreach ($finder as $f) {
                $data .= (new File($f,$file->getConfig()))
                    ->build();
            }

            $sources = str_replace($a[0][$i], $data, $sources);
        }

        $this->sources = $sources;

    }

    protected function search($path, $mods = null)
    {
        if (substr($path, mb_strlen($path) - 1) != '*') {
            return [new SplFileInfo($path)];
        }
        $modifiers = [];
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

        $finder = new Finder();
        $finder
            ->files()
            ->in(substr($path, 0, mb_strlen($path) - 1));
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
