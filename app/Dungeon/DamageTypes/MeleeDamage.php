<?php

namespace Dungeon\DamageTypes;

class MeleeDamage implements DamageTypeContract
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
