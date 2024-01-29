<?php

namespace App\Exceptions;

use App\Ingredients\Ingredient;
use Exception;

class IngredientNotEnabledException extends Exception
{
    public function __construct(Ingredient $ingredient)
    {
        parent::__construct(
            sprintf(
                "Error: %s is not enabled ingredient in this storage!\n",
                ucfirst($ingredient)
            )
        );
    }
}
