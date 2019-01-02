<?php

namespace App\Dungeon\Traits;

use App\Entity;
use App\Dungeon\Traits\Pickupable;

trait HasInventory
{
    public function items()
    {
        return $this->hasMany(Entity::class);
    }

    public function hold(Pickupable $item)
    {
        $this->inventory();
    }
}