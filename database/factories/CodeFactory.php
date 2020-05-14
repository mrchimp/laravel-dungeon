<?php

use Dungeon\Entities\Locks\Code;
use Faker\Generator as Faker;

$factory->define(Code::class, function (Faker $faker) {
    return [
        'name' => 'Code',
        'description' => 'Can be used to unlock a lock',
        'code' => (string)$faker->numberBetween(0, 9999),
    ];
});
