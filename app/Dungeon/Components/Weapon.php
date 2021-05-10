<?php

namespace Dungeon\Components;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Weapon extends Component
{
    protected $fillable = [
        'blunt',
        'stab',
        'projectile',
        'fire',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class, 'weapon_id');
    }
}
