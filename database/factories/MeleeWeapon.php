<?php

use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\Weapons\Melee\MeleeWeapon;
use Faker\Generator as Faker;

$factory->define(MeleeWeapon::class, function (Faker $faker) {
    return [
        'name' => 'Rock',
        'description' => 'You can hit people with it.',
        'damage_types' => [
            MeleeDamage::class => 50,
        ],
    ];
});
