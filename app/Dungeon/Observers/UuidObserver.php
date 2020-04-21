<?php

namespace Dungeon\Observers;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class UuidObserver
{
    public function saving(Model $model)
    {
        if (empty($model->uuid)) {
            $model->uuid = Uuid::uuid1()->toString();
        }
    }
}
