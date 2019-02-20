<?php

use Faker\Generator as Faker;
use Dungeon\Entity;

$factory->define(Entity::class, function (Faker $faker) {
    return [
        'name' => 'Entity',
        'description' => 'A generic entity that hasn\'t had its description set.',
        'class' => Dungeon\Entity::class,
        'data' => [],
    ];
});
