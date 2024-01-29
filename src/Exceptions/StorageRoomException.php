<?php

namespace App\Exceptions;

use Exception;

class StorageRoomException extends Exception
{

    public function __construct(string $ingredientType, int $needed, int $found)
    {
        parent::__construct(
            sprintf(
                "Error: %s can not be gathered: needed: %d, found: %d\n",
                ucfirst($ingredientType),
                $needed,
                $found
            )
        );
    }
}
