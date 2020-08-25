<?php

namespace Dungeon\Traits;

use Dungeon\Entity;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasInventory
{
    public function inventory(): HasMany
    {
        return $this->hasMany(Entity::class, 'container_id');
    }

    public function canBeLooted(): bool
    {
        return true;
    }
}
