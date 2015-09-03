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

use SplFileObject;

/**
 * Class CssDriver
 * @package Serafim\Asset\Drivers
 */
class CssDriver implements DriverInterface
{
    /**
     * @param SplFileObject $file
     * @param string $sources
     * @return string
     */
    public function build(SplFileObject $file, $sources)
    {
        return $sources;
    }
}
