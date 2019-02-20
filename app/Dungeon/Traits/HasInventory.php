<?php

namespace Dungeon\Traits;

use Dungeon\Traits\Pickupable;
use App\Entity;

trait HasInventory
{
    public function inventory()
    {
        return $this->hasMany(Entity::class, 'owner_id');
    }
}
