<?php

namespace Dungeon\Entities\People;

use Dungeon\Entity;
use Dungeon\Traits\HasHealth;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasInventory;

class Body extends Entity
{
    use HasHealth,
        HasInventory,
        HasApparel;

    public function getSerializable()
    {
        return [
            'health',
        ];
    }

    public function user()
    {
        throw new \Exception('You want to use the "owner" relationship, not "user".');
    }
}
