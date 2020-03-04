<?php

namespace Dungeon\Entities;

use Dungeon\Contracts\WeaponInterface;
use Dungeon\Entity;
use Dungeon\Traits\CanBeUsedAsWeapon;

class Weapon extends Entity implements WeaponInterface
{
    use CanBeUsedAsWeapon;

    public function getVerbs()
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'attack';

        return $verbs;
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
                'damage_types' => 50,
            ]
        );
    }
}
