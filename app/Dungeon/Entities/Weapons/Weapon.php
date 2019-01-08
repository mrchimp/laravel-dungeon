<?php

namespace App\Dungeon\Entities;

use App\Entity;

class Weapon extends Entity
{
    public function getType()
    {
        return 'weapon';
    }
}
