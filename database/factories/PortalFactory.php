<?php

use Dungeon\Portal;
use Faker\Generator as Faker;

$factory->define(Portal::class, function (Faker $faker) {
    return [
        'class' => Portal::class,
    ];
});
