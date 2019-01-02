<?php

namespace App\Observers;

use App\Entity;

class EntityObserver
{
    /**
     * Manipulate the model before saving
     */
    public function saving(Entity $entity)
    {
        // Before save
    }
}