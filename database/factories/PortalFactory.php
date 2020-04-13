<?php

use Dungeon\Portal;
use Faker\Generator as Faker;

$factory->define(Portal::class, function (Faker $faker) {
    return [
        'description' => 'It is like a door or a hole you can fit through.',
    ];
});
