<?php

namespace Dungeon\Traits;

use Dungeon\DamageTypes\MeleeDamage;

trait CanBeUsedAsWeapon
{
    /**
     * Attack a target with this weapon
     *
     * @return
     */
    public function attack($target)
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
     *
     * @return array
     */
    public function damageTypes()
    {
        return [
            MeleeDamage::class => 10,
        ];
    }
}
