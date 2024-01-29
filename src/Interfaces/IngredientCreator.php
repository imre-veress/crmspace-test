<?php

namespace App\Interfaces;

use App\Ingredients\Ingredient;

interface IngredientCreator
{
    public function create(string $ingredientType): Ingredient;
}
