<?php

namespace Dungeon\Entities\Weapons;

use Dungeon\Contracts\WeaponInterface;
use Dungeon\Entity;
use Dungeon\Traits\CanBeUsedAsWeapon;

class Weapon extends Entity implements WeaponInterface
{
    use CanBeUsedAsWeapon;

    protected $fillable = [
        'name',
        'description',
        'damage_types',
    ];

    public function getVerbs(): array
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
    public function getSerializable(): array
    {
        return array_merge(
            parent::getSerializable(),
            [
                'damage_types' => [],
            ]
        );
    }
}
