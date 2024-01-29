<?php

namespace App\Ingredients;

class Fish extends Ingredient
{
    public function __construct()
    {
        $this->name = 'fish';
        $this->size = 15;
    }
}
