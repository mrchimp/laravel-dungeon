<?php

namespace Dungeon\Traits;

use App\Entity;

trait ContainsItems
{
    public function items()
    {
        return $this->hasMany(Entity::class);
    }

    public function canContainItems()
    {
        return $this->can_contain_items;
    }

    public function hold(Entity $item)
    {
        if (!$this->canContainItems()) {
            return false;
        }

        $this->items()->save($item);

        return true;
    }

    public function take(Entity $item)
    {
        $this->items()->detach($item);
    }
}
