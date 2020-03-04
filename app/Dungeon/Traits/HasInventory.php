<?php

namespace Dungeon\Traits;

use Dungeon\Entity;

trait HasInventory
{
    public function inventory()
    {
        return $this->hasMany(Entity::class, 'container_id');
    }
}
