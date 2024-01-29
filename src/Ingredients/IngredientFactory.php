<?php

namespace App\Ingredients;

use App\Interfaces\IngredientCreator;

class IngredientFactory implements IngredientCreator
{
    public function create(string $ingredientType): Ingredient
    {
        return match ($ingredientType) {
            'Fish' => new Fish(),
            'Flour' => new Flour(),
            'Meet' => new Meet(),
            'Rice' => new Rice(),
            'Salad' => new Salad(),
        };
    }
}
