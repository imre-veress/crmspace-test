<?php

namespace App\Containers;

use App\Ingredients\Fish;
use App\Ingredients\Meet;
use App\Ingredients\Salad;

class Refrigerator extends Container
{
    protected int $space = 100;
    protected int $freeSpace = 100;

    protected array $enabledIngredient = [Meet::class, Fish::class, Salad::class];
    protected bool $multiple = true;

    public function __construct(string $name)
    {
        parent::__construct(str_replace('Refrigerator', 'Fridge', $name));
    }
}
