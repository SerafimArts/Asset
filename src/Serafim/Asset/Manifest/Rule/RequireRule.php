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

use Symfony\Component\Finder\SplFileInfo;

/**
 * Class RequireRule
 * @package Serafim\Asset\Manifest\Rule
 */
class RequireRule
    implements RequireInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var array
     */
    protected $ignore;

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @param SplFileInfo $file
     * @param $path
     * @param array $ignore
     */
    public function __construct(SplFileInfo $file, $path, array $ignore = [])
    {
        $this->path = $file->getPath() . '/' . $path;
        $this->file = new SplFileInfo(
            $this->path,
            dirname($path),
            $path
        );
        $this->ignore       = $ignore;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        if (!$this->files) {
            if (!in_array($this->file->getRelativePathname(), $this->ignore)) {
                $this->files = [$this->file];
            }
        }
        return $this->files;
    }

    /**
     * @return array
     */
    public function getRelatives()
    {
        return [$this->files[0]->getRelativePathname()];
    }

    /**
     * @param $path
     * @return int
     */
    public static function match($path)
    {
        return preg_match('#^.*?^\*(\.[a-z]+)?$#u', $path);
    }
}
