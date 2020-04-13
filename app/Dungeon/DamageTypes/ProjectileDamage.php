<?php

namespace Dungeon\DamageTypes;

class ProjectileDamage implements DamageTypeContract
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
