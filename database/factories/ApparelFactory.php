<?php

use Dungeon\Apparel\Apparel;
use Faker\Generator as Faker;

$factory->define(Apparel::class, function (Faker $faker) {
    return [
        'name' => 'Hat',
        'description' => 'You can wear it on yer head.',
    ];
});
