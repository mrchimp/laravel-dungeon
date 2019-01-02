<?php

use Faker\Generator as Faker;
use App\Entity;

$factory->define(Entity::class, function (Faker $faker) {
    return [
        'name' => 'Entity',
        'description' => 'A generic entity that hasn\'t had its description set.',
        'class' => App\Entity::class,
        'data' => [],
    ];
});
