<?php

namespace Dungeon\Collections;

use Dungeon\Entity;
use Illuminate\Database\Eloquent\Collection;

class EntityCollection extends Collection
{
    /**
     * Create a new collection.
     *
     * @param  mixed  $items
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);

        $this->items = array_map(function ($item) {
            if ($item instanceof Entity) {
                return Entity::replaceClass($item);
            } else {
                return $item;
            }
        }, $this->items);
    }
}
