<?php

namespace App\Ingredients;

class Ingredient
{
    protected string $name;
    protected int $size;

    public function getSize(): int
    {
        return $this->size;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

}
