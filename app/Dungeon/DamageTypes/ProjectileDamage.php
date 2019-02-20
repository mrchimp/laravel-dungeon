<?php

namespace Dungeon\DamageTypes;

use Dungeon\DamageTypes\MeleeDamage;

class ProjectileDamage implements DamageType
{
    /**
     * Get the name of this damage type
     *
     * @return string
     */
    public function name()
    {
        return 'projectile';
    }
}
