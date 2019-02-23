<?php

use Faker\Generator as Faker;
use Dungeon\Entities\People\Body;

$factory->define(Body::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => 'A body for testing purposes.',
    ];
});
