<?php

namespace Dungeon\Components;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Consumable extends Component
{
    protected $fillable = [
        'hp',
        'taste',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class, 'consumable_id');
    }
}
