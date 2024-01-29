<?php

namespace App\Ingredients;

class Salad extends Ingredient
{
    public function __construct()
    {
        $this->name = 'salad';
        $this->size = 30;
    }
}
