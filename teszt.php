<?php

require_once 'autoloader.php';

const LOG = false;

use App\Controllers\StorageRoom;
use App\Exceptions\CanNotBeStoredException;
use App\Exceptions\ContainerNotAllowedException;
use App\Exceptions\IngredientNotAllowedException;
use App\Exceptions\StorageRoomException;

$room = new StorageRoom();

try {
    $room->addContainers('box', 3);
    $room->addContainers('refrigerator', 2);
} catch (ContainerNotAllowedException $e) {
    echo $e->getMessage();
    if ($room->getContainersCount() === 0) {
        exit;
    }
}

try {
    $room->storeIngredient('meet', 6);
    $room->storeIngredient('meet', 6);
    $room->storeIngredient('salad', 1);
    $room->storeIngredient('rice', 10);
    $room->storeIngredient('fish', 1);
} catch (IngredientNotAllowedException|CanNotBeStoredException $e) {
    echo $e->getMessage();
}

echo $room->ItemizedInventory();
echo $room->ingredientInventory().PHP_EOL;

try {
    $room->getIngredient('salad', 1);
    $room->getIngredient('meet', 8);
    $room->getIngredient('rice', 8);
} catch (IngredientNotAllowedException|StorageRoomException $e) {
    echo $e->getMessage();
}

echo $room->ItemizedInventory();
echo $room->ingredientInventory().PHP_EOL;

try {
    $room->getIngredient('rice', 3);
} catch (IngredientNotAllowedException|StorageRoomException $e) {
    echo $e->getMessage();
}
