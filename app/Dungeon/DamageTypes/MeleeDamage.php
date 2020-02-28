<?php

namespace Dungeon\DamageTypes;

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
