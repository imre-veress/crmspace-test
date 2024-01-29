<?php

namespace App\Exceptions;

use Exception;

class CanNotBeStoredException extends Exception
{

    /**
     * @param  string  $ingredientType
     * @param  int  $quantity
     */
    public function __construct(string $containerType, int $quantity)
    {
        parent::__construct(
            sprintf(
                "Error: You cannot store %d units of %s!\n",
                $quantity,
                $containerType,
            )
        );
    }
}
