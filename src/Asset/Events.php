<?php namespace Asset;

    /**
     * This file is part of Asset package.
     *
     * serafim <nesk@xakep.ru> (03.06.2014 17:51)
     *
     * For the full copyright and license information, please view the LICENSE
     * file that was distributed with this source code.
     */

/**
 * Class Events
 * @package Asset
 */
class Events
{
    public function beforeDriver($name, callable $callback)
    {
    }

    public function afterDriver($name, callable $callback)
    {
    }

    public function beforeType($type, callable $callback)
    {
    }

    public function afterType($type, callable $callback)
    {
    }

    public function before(callable $callback)
    {
    }

    public function after(callable $callback)
    {
    }
}