<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait HasUuid
{
    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->uuid = Uuid::uuid1()->toString();
        });
    }
}