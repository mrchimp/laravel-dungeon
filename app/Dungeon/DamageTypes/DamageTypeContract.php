<?php

namespace Dungeon\DamageTypes;

interface DamageTypeContract
{
    /**
     * Get the name of this damage type
     *
     * @return string
     */
    public function name();
}
