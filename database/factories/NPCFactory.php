<?php

use Dungeon\NPC;
use Faker\Generator as Faker;

$factory->define(NPC::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => 'NPC for testing purposes.',
    ];
});
