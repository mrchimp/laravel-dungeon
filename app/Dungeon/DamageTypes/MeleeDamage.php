<?php

namespace App\Dungeon\DamageTypes;

use App\Dungeon\DamageTypes\MeleeDamage;

class MeleeDamage implements DamageType
{
    /**
     * Get the name of this damage type
     *
     * @return string
     */
    public function name()
    {
        return 'melee';
    }
}
