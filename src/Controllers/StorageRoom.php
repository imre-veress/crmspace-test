<?php

namespace App\Controllers;

use App\Containers\ContainerFactory;
use App\Enums\Containers;
use App\Enums\Ingredients;
use App\Exceptions\CanNotBeStoredException;
use App\Exceptions\ContainerNotAllowedException;
use App\Exceptions\IngredientNotAllowedException;
use App\Exceptions\StorageRoomException;
use App\Ingredients\IngredientFactory;

class StorageRoom
{
    private array $containers;

    /**
     * @throws ContainerNotAllowedException
     */
    public function addContainer(string $containerType): void
    {
        if (!in_array($containerType, array_column(Containers::cases(), 'value'))) {
            throw new ContainerNotAllowedException($containerType);
        }

        $containerType = ucfirst($containerType);
        $newContainerName = (empty($this->containers)) ? $containerType.'1' : $this->createNewContainerName(
            $containerType
        );

        $this->containers[] = (new ContainerFactory)->create($containerType, $newContainerName);
    }

    /**
     * @throws ContainerNotAllowedException
     */
    public function addContainers(string $containerType, int $quantity): void
    {
        if (LOG) {
            echo "Add $quantity $containerType.\n";
        }

        for ($i = 1; $i <= $quantity; $i++) {
            $this->addContainer($containerType);
        }
    }

    /**
     * @throws IngredientNotAllowedException
     * @throws CanNotBeStoredException
     */
    public function storeIngredient(string $ingredientType, int $quantity): void
    {
        if (LOG) {
            echo "Add $quantity $ingredientType.\n";
        }

        $this->enabledIngredient($ingredientType);

        $containerIndex = 0;

        $ingredient = (new IngredientFactory())->create(ucfirst($ingredientType));

        do {
            if ($containerIndex > ($this->getContainersCount() - 1)) {
                throw new CanNotBeStoredException($ingredientType, $quantity);
            }

            $quantity = $this->containers[$containerIndex]->store($ingredient, $quantity);
            $containerIndex++;
        } while ($quantity > 0);
    }

    /**
     * @throws IngredientNotAllowedException
     * @throws StorageRoomException
     */
    public function getIngredient(string $ingredientType, int $quantity): void
    {
        if (LOG) {
            echo "Get $quantity $ingredientType.\n";
        }

        $this->enabledIngredient($ingredientType);

        $storedIngredient = $this->getStoredIngredients();

        if (!array_key_exists($ingredientType, $storedIngredient) || $storedIngredient[$ingredientType] < $quantity) {
            throw new StorageRoomException($ingredientType, $quantity, $storedIngredient[$ingredientType] ?? 0);
        }

        for ($i = $this->getContainersCount() - 1; $i >= 0; $i--) {
            $quantity = $this->containers[$i]->get($ingredientType, $quantity);
        }
    }

    public function ingredientInventory(): string
    {
        $storedIngredients = $this->getStoredIngredients();

        $ingredients = '
Ingredients list:
--------------------------------------------
';
        foreach ($storedIngredients as $ingredient => $quantity) {
            $ingredients .= "* $ingredient\t(total quantity: $quantity)\n";
        }

        return $ingredients;
    }

    public function ItemizedInventory(): string
    {
        asort($this->containers, SORT_STRING);

        $inventory = '
Stock by containers:
--------------------------------------------
';

        foreach ($this->containers as $container) {
            $inventory .= $container."\n";
        }

        return $inventory;
    }

    public function getContainersCount(): int
    {
        return count($this->containers);
    }


    private function createNewContainerName(string $containerType): string
    {
        $i = 1;
        foreach ($this->containers as $store) {
            $classNameArray = explode('\\', get_class($store));
            $className = $classNameArray[count($classNameArray) - 1];

            if ($className === $containerType) {
                $i++;
            }
        }

        return $containerType.$i;
    }

    /**
     * @return array
     */
    private function getStoredIngredients(): array
    {
        $storedIngredients = [];
        foreach ($this->containers as $container) {
            foreach ($container->getStoredIngredients() as $ingredient) {
                $key = $ingredient['ingredient']->getName();
                $quantity = $ingredient['quantity'];

                if (array_key_exists($key, $storedIngredients)) {
                    $storedIngredients[$key] += $quantity;
                } else {
                    $storedIngredients[$key] = $quantity;
                }
            }
        }
        return $storedIngredients;
    }

    /**
     * @param  string  $ingredientType
     * @return void
     * @throws IngredientNotAllowedException
     */
    private function enabledIngredient(string $ingredientType): void
    {
        if (!in_array($ingredientType, array_column(Ingredients::cases(), 'value'))) {
            throw new IngredientNotAllowedException($ingredientType);
        }
    }
}
