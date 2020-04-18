<?php

namespace Dungeon\Traits;

use Dungeon\DamageTypes\MeleeDamage;
use Illuminate\Support\Arr;

trait CanBeUsedAsWeapon
{
    /**
     * Attack a target with this weapon
     */
    public function attack($target): array
    {
        $damages = [];

        foreach ($this->damage_types as $damage_type => $damage_amount) {
            $target->hurt($damage_amount);
            $damages[$damage_type] = $damage_amount;
        }

        $target->save();
        $total_damage = array_sum($damages);

        $damages['total'] = $total_damage;

        return $damages;
    }

    /**
     * The type of damage this weapon deals
     */
    public function damageTypes(): array
    {
        // dump($this->damage_types, $this);
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
