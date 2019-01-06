<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;

class SerializableObserver
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