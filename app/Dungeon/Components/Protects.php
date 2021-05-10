<?php

namespace Dungeon\Components;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Protects extends Component
{
    protected $table = 'protectors';

    protected $fillable = [
        'blunt',
        'stab',
        'projectile',
        'fire',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class, 'protects_id');
    }
}
