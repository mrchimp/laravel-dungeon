<?php

namespace Dungeon\DamageTypes;

interface DamageType
{
    /**
     * Get the name of this damage type
     *
     * @return string
     */
    public function name();
}
