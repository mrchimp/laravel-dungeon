<?php

namespace Dungeon\Components;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Takeable extends Component
{
    protected $table = 'takeables';

    protected $fillable = [
        'weight',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class, 'takeable_id');
    }
}
