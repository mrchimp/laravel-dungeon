<?php

use Dungeon\Entities\Food\Food;
use Faker\Generator as Faker;

$factory->define(Food::class, function (Faker $faker) {
    return [
        'name' => 'Piece of Food',
        'description' => 'A generic piece of food that should have been named.',
        'serialized_data' => [],
    ];
});
