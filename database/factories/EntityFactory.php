<?php

use Dungeon\Entity;
use Faker\Generator as Faker;

$factory->define(Entity::class, function (Faker $faker) {
    return [
        'name' => 'Entity',
        'description' => 'A generic entity that hasn\'t had its description set.',
        'class' => Dungeon\Entity::class,
        'serialized_data' => [],
    ];
});
