<?php
namespace Asset\Driver;

use SplFileObject;
use Asset\File\Collection;

interface DriverInterface
{
    public function __construct(Collection $items, SplFileObject $file);
    public function getDepending();
    public function make();
    public function getResult();
    public function getType();
}