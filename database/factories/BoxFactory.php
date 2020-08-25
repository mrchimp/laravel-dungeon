<?php

use Dungeon\Entities\Containers\Box;
use Faker\Generator as Faker;

$factory->define(Box::class, function (Faker $faker) {
    return [
        'name' => 'Box',
        'description' => 'You can put things in it.',
    ];
});
