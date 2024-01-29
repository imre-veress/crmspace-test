<?php

namespace App\Containers;

use App\Ingredients\Ingredient;

class Container
{
    protected string $name;
    protected int $space;
    protected int $freeSpace;
    protected array $stored = [];
    protected array $enabledIngredient = [];
    protected bool $multiple = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function __toString(): string
    {
        $used = $this->space - $this->freeSpace;
        $usedPercent = round(($used / $this->space), 2) * 100;

        $str = "$this->name\tspace: {$this->space} , used: {$used} / {$usedPercent}%\n";

        if (!empty($this->stored)) {
            foreach ($this->stored as $item) {
                $space = $item['ingredient']->getSize() * $item['quantity'];
                $str .= "* {$item['ingredient']}\t\t:\t{$item['quantity']} (space: $space)\n";
            }
        }

        return $str;
    }

    public function store(Ingredient $ingredient, int $quantity): int
    {
        if (
            $this->notAllowedIngredient($ingredient) ||
            ($this->freeSpace === 0) ||
            $this->onlyOneTypeOfIngredient($ingredient)
        ) {
            return $quantity;
        }

        $storedIngredient = [
            'ingredient' => $ingredient,
            'quantity' => $quantity,
        ];

        $enabledIngredientQuantity = (int)round($this->freeSpace / $ingredient->getSize());

        $foundIngredient = $this->searchIngredient($ingredient);

        if ($enabledIngredientQuantity < $quantity) {
            $storedIngredient['quantity'] = $enabledIngredientQuantity;
            $quantity -= $enabledIngredientQuantity;
            $this->freeSpace -= $enabledIngredientQuantity * $ingredient->getSize();
        } else {
            $this->freeSpace -= $quantity * $ingredient->getSize();
            $quantity = 0;
        }

        if ($foundIngredient['found']) {
            $this->stored[$foundIngredient['storeIndex']]['quantity'] += $enabledIngredientQuantity;
        } else {
            if ($enabledIngredientQuantity > 0) {
                $this->stored[] = $storedIngredient;
            }
        }

        return $quantity;
    }

    public function get(string $ingredientType, int $quantity): int
    {
        if (!in_array($ingredientType, $this->getStoredIngredientNames())) {
            return $quantity;
        }

        foreach ($this->stored as $index => $item) {
            if ($item['ingredient']->getName() === $ingredientType) {
                $quantity = $this->settingAndQueryingIngredientQuantity($item, $quantity, $index);
            }
        }

        return $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStoredIngredients(): array
    {
        return $this->stored;
    }

    private function getStoredIngredientNames(): array
    {
        $storedIngredientNames = [];
        foreach ($this->stored as $item) {
            $storedIngredientNames[] = $item['ingredient']->getName();
        }

        return $storedIngredientNames;
    }

    private function searchIngredient(Ingredient $ingredient): array
    {
        $found = false;
        $storeIndex = 0;

        foreach ($this->stored as $index => $item) {
            if ($item['ingredient']->getName() === $ingredient->getName()) {
                $found = true;
                $storeIndex = $index;
            }
        }

        return [
            'found' => $found,
            'storeIndex' => $storeIndex,
        ];
    }

    /**
     * @param  Ingredient  $ingredient
     * @return bool
     */
    private function notAllowedIngredient(Ingredient $ingredient): bool
    {
        return !in_array(get_class($ingredient), $this->enabledIngredient);
    }

    /**
     * @param  Ingredient  $ingredient
     * @return bool
     */
    private function onlyOneTypeOfIngredient(Ingredient $ingredient): bool
    {
        return (
            $this->stored !== [] &&
            !$this->multiple &&
            ($this->stored[0]['ingredient']->getName() !== $ingredient->getName())
        );
    }

    /**
     * @param  mixed  $item
     * @param  mixed  $quantity
     * @param  int|string  $index
     * @return int|mixed
     */
    private function settingAndQueryingIngredientQuantity(mixed $item, mixed $quantity, int|string $index): mixed
    {
        if ($item['quantity'] >= $quantity) {
            $item['quantity'] -= $quantity;
            $this->freeSpace = $this->space - ($item['quantity'] * $item['ingredient']->getSize());
            $quantity = 0;

            if ($item['quantity'] === 0) {
                unset($this->stored[$index]);
            } else {
                $this->stored[$index]['quantity'] = $item['quantity'];
            }
        } else {
            $quantity -= $item['quantity'];
            unset($this->stored[$index]);
            $this->freeSpace = $this->freeSpace + $item['quantity'] * $item['ingredient']->getSize();
        }

        return $quantity;
    }
}
