<?php

namespace App\Dungeon\Entities;

use App\Entity;

class Weapon extends Entity
{
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
}
