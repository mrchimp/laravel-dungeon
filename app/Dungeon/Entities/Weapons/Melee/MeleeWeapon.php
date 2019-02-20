<?php

namespace Dungeon\Entities\Weapons\Melee;

use Dungeon\Entities\Weapon;
use Dungeon\DamageTypes\MeleeDamage;

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
