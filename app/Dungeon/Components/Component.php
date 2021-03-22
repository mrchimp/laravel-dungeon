<?php

namespace Dungeon\Components;

use Dungeon\Contracts\ComponentInterface;
use Dungeon\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

abstract class Component extends Model implements ComponentInterface
{
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }
}
