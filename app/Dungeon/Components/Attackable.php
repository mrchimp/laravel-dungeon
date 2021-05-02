<?php

namespace Dungeon\Components;

class Attackable extends Component
{
    protected $table = 'attackables';

    protected $fillable = [
        'hp',
        'blunt',
        'stab',
        'projectile',
        'fire',
        'entity_id',
    ];
}
