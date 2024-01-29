<?php

namespace App\Containers;

use App\Interfaces\ContainerCreator;

class ContainerFactory implements ContainerCreator
{
    public function create(string $containerType, string $name): Container
    {
        return match ($containerType) {
            'Box' => new Box($name),
            'Refrigerator' => new Refrigerator($name)
        };
    }
}
