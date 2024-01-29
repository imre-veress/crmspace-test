<?php

namespace App\Containers;

use App\Ingredients\Flour;
use App\Ingredients\Rice;
use App\Ingredients\Salad;

class Box extends Container
{
    protected int $space = 40;
    protected int $freeSpace = 40;

    protected array $enabledIngredient = [Salad::class, Flour::class, Rice::class];
}
