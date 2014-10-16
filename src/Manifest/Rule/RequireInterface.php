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
 * Interface RequireInterface
 * @package Serafim\Asset\Manifest\Rule
 */
interface RequireInterface
{
    /**
     * @param SplFileInfo $file
     * @param $path
     * @param array $ignore
     */
    public function __construct(SplFileInfo $file, $path, array $ignore = []);

    /**
     * @return mixed
     */
    public function getFiles();

    /**
     * @return mixed
     */
    public function getRelatives();

    /**
     * @param $path
     * @return mixed
     */
    public static function match($path);
}
