<?php

namespace App\Exceptions;

use Exception;

class IngredientNotAllowedException extends Exception
{
    public function __construct(string $ingredientType)
    {
        parent::__construct(
            sprintf(
                "Error: %s is not a permitted ingredient in this storage room!\n",
                ucfirst($ingredientType)
            )
        );
    }
}
