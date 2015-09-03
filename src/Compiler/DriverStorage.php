<?php
/**
 * This file is part of Asset3 package.
 *
 * @author Serafim <nesk@xakep.ru>
 * @date   03.09.2015 15:59
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Serafim\Asset\Compiler;

use SplFixedArray;
use Serafim\Asset\Drivers\DriverInterface;

/**
 * Class DriverStorage
 * @package Serafim\Asset\Compiler
 */
class DriverStorage
{
    /**
     * Drivers array
     *
     * @var DriverInterface[]
     */
    protected $drivers = [];

    /**
     * Last index of drivers array
     *
     * @var int
     */
    protected $driversLastId = 0;

    /**
     * <extension => driverId> hash map
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * @return DriverStorage
     */
    public function __construct()
    {
        $this->drivers = new SplFixedArray(10);
    }

    /**
     * @param DriverInterface $driver
     * @param array $extensions
     * @return $this
     */
    public function attach(DriverInterface $driver, array $extensions)
    {
        $this->drivers[$this->driversLastId] = $driver;

        foreach ($extensions as $extension) {
            $this->extensions[$extension] = $this->driversLastId;
        }

        $this->driversLastId++;

        return $this;
    }

    /**
     * @param string $extension
     * @return null|DriverInterface
     */
    public function detectDriver($extension)
    {
        if (array_key_exists($extension, $this->extensions)) {
            return $this->drivers[$this->extensions[$extension]];
        }
        return null;
    }

}
