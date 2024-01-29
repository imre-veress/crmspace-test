<?php

namespace App\Ingredients;

class Rice extends Ingredient
{
    public function __construct()
    {
        $this->name = 'rice';
        $this->size = 8;
    }
}
