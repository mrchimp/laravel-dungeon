<?php

use Dungeon\Entities\Locks\Key;
use Faker\Generator as Faker;

$factory->define(Key::class, function (Faker $faker) {
    return [
        'name' => 'key',
        'description' => 'A rusty old key.',
    ];
});
