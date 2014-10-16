<?php
/**
 * This file is part of Asset package.
 *
 * Serafim <nesk@xakep.ru> (15.10.2014 21:23)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */ 
namespace Serafim\Asset\Manifest\Rule;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class MultipleRequireRule
 * @package Serafim\Asset\Manifest\Rule
 */
class MultipleRequireRule
    implements RequireInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var
     */
    protected $ignore;

    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $files;

    /**
     * @var bool
     */
    protected $recursive = false;

    /**
     * @param SplFileInfo $file
     * @param $path
     * @param array $ignore
     */
    public function __construct(SplFileInfo $file, $path, array $ignore = [])
    {
        $source             = $file->getPath() . '/' . $path;

        $this->name         = $this->getExtension($source);
        $this->path         = $this->getRealPath($source, $this->name);
        $this->recursive    = $this->isRecursive($this->path);
        $this->ignore       = $ignore;

        if ($this->recursive) {
            $this->path = substr($this->path, 0, mb_strlen($this->path) - 1);
        }
    }

    /**
     * @param $source
     * @param $name
     * @return string
     */
    protected function getRealPath($source, $name)
    {
        return mb_substr($source, 0, mb_strlen($source) - mb_strlen($name));
    }

    /**
     * @param $path
     * @return bool
     */
    protected function isRecursive($path)
    {
        return substr($path, mb_strlen($path) - 1) == '*';
    }

    /**
     * @param $path
     * @return mixed
     */
    protected function getExtension($path)
    {
        preg_match_all('#\*(\.[a-z]+)?$#u', $path, $matches);
        return $matches[0][0];
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        if (!$this->files) {
            $finder = new Finder;
            $finder
                ->files()
                ->in($this->path)
                ->name($this->name);

            if (!$this->recursive) {
                $finder->depth('== 0');
            }

            foreach ($this->ignore as $ignore) {
                $finder->notName($ignore);
            }

            $this->files = iterator_to_array(
                $finder->getIterator()
            );
        }
        return $this->files;
    }

    /**
     * @return array
     */
    public function getRelatives()
    {
        $result = [];
        foreach ($this->getFiles() as $file) {
            $result[] = $file->getRelativePathname();
        }
        return $result;
    }

    /**
     * @param $path
     * @return int
     */
    public static function match($path)
    {
        return preg_match('#^.*?/\*(\*?)(\.[a-z]+)?$#isu', $path);
    }
}
