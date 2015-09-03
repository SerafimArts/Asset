<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 16:00
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Drivers;

use SplFileObject;

/**
 * Interface DriverInterface
 * @package Serafim\Asset\Drivers
 */
interface DriverInterface
{
    /**
     * @param SplFileObject $file
     * @param string $sources
     * @return string
     */
    public function build(SplFileObject $file, $sources);
}
