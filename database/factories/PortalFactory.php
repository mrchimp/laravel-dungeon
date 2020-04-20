<?php

use Dungeon\Portal;
use Faker\Generator as Faker;

$factory->define(Portal::class, function (Faker $faker) {
    return [
        'name' => 'A wooden door',
        'description' => 'A door made for testing. It may have a key or code lock attached.',
        'class' => Portal::class,
    ];
});
