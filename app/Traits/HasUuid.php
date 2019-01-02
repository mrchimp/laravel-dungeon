<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;

trait HasUuid
{
    public static function boot()
    {
        parent::boot();
dd('boooting');
        self::creating(function($model) {
            dd('creating');
            $model->uuid = Uuid::uuid1()->toString();
        });
    }
}