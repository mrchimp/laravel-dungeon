<?php

namespace Dungeon\Observers;

use Illuminate\Database\Eloquent\Model;

class HasOwnClassObserver
{
    public function creating(Model $entity)
    {
        $entity->class = get_class($entity);
    }
}
