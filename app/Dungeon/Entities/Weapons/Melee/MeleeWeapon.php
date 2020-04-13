<?php

namespace Dungeon\Entities\Weapons\Melee;

use Dungeon\DamageTypes\MeleeDamage;
use Dungeon\Entities\Weapons\Weapon;

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
