<?php

use Dungeon\Room;
use Faker\Generator as Faker;

$factory->define(Room::class, function (Faker $faker) {
    return [
        'description' => 'A room.',
    ];
});
