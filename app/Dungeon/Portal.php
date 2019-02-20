<?php

namespace Dungeon;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Portal extends Pivot
{
    protected $table = 'portals';

    protected $fillable = [
        'description',
    ];

    public function getDescription()
    {
        return $this->description;
    }
}
