<?php

namespace Dungeon\Entities;

use Dungeon\Entity;
use Dungeon\DamageTypes\MeleeDamage;

class Weapon extends Entity
{
    /**
     * Default serializable attributes
     *
     * @var array
     */
    public $serializable = [
        'damage_types' => [
            MeleeDamage::class => 10,
        ],
    ];

    public function getType()
    {
        return 'weapon';
    }

    public function getVerbs()
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'attack';

        return $verbs;
    }

    /**
     * The type of damage this weapon deals
     *
     * @return array
     */
    public function damageTypes()
    {
        return [];
    }

    /**
     * Get the names of attributes to be serialized
     *
     * @return array
     */
    public function getSerializable()
    {
        return array_merge(
            parent::getSerializable(),
            [
                'damage_types'
            ]
        );
    }

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
}
