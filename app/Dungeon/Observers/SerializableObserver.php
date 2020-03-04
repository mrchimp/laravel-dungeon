<?php

namespace Dungeon\Observers;

use Illuminate\Database\Eloquent\Model;

class SerializableObserver
{
    /**
     * Manipulate the model before saving
     */
    public function saving(Model $entity)
    {
        $entity->applyDefaultSerializableAttributes();
        $entity->serializeAttributes();
    }

    public function saved(Model $entity)
    {
        $entity->deserializeAttributes();
    }

    public function retrieved(Model $entity)
    {
        $entity->deserializeAttributes();
    }
}
