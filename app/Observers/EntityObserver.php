<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class EntityObserver
{
    /**
     * Manipulate the model before saving
     */
    public function saving(Model $entity)
    {
        $entity->serialiseAttributes();
    }

    public function retrieved(Model $entity)
    {
        $entity->deserialiseAttributes();
    }
}