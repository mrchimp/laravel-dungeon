<?php

namespace Dungeon\Traits;

use Dungeon\Traits\Pickupable;
use Dungeon\Entity;

trait HasInventory
{
    public function inventory()
    {
        return $this->hasMany(Entity::class, 'owner_id');
    }
}
