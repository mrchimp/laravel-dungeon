<?php

namespace App\Dungeon\Entities\Weapons\Melee;

use App\Dungeon\Entities\Weapon;
use App\Dungeon\DamageTypes\MeleeDamage;

/**
 * A generic melee weapon.
 */
class MeleeWeapon extends Weapon
{

    /**
     * Default serializable attributes
     *
     * @var array
     */
    public $serializable = [
        'damage_types' => [
            MeleeDamage::class => 50,
        ],
    ];
}
