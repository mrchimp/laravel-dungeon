<?php

namespace Dungeon\Collections;

use Dungeon\Entity;
use Dungeon\Entities\People\Body;
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

    public function people(Body $exclude = null)
    {
        $people = $this
            ->filter(function ($item) {
                return get_class($item) === Body::class && $item->ownerType() === 'user';
            });

        if (!is_null($exclude)) {
            return $people->filter(function ($item) use ($exclude) {
                return $item->id !== $exclude->id;
            });
        }

        return $people;
    }

    public function npcs()
    {
        return $this->filter(function ($item) {
            return get_class($item) === Body::class && $item->ownerType() === 'npc';
        });
    }

    public function items()
    {
        return $this->filter(function ($item) {
            return get_class($item) !== Body::class;
        });
    }
}
