<?php

namespace App\Exceptions;

use Exception;

class ContainerNotAllowedException extends Exception
{
    public function __construct(string $containerType)
    {
        parent::__construct(
            sprintf(
                "Error: %s is not allowed container in this storage!\n",
                ucfirst($containerType)
            )
        );
    }
}
