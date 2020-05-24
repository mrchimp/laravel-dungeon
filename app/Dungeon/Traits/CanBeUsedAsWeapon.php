<?php

namespace Dungeon\Traits;

use Illuminate\Support\Arr;

trait CanBeUsedAsWeapon
{
    /**
     * The type of damage this weapon deals
     */
    public function damageTypes(): array
    {
        return $this->damage_types ? $this->damage_types : [];
    }

    /**
     * The amount of a given damage type this weapon deals
     */
    public function damageType(string $type): int
    {
        return Arr::get($this->damageTypes(), $type, 0);
    }
}
