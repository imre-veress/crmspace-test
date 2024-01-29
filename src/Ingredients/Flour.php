<?php

namespace App\Ingredients;

class Flour extends Ingredient
{
    public function __construct()
    {
        $this->name = 'flour';
        $this->size = 15;
    }
}
