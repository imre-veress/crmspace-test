<?php

namespace App\Interfaces;

use App\Containers\Container;

interface ContainerCreator
{
    public function create(string $containerType, string $name): Container;
}
