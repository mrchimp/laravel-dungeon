<?php

namespace Dungeon\Components;

class Protects extends Component
{
    protected $table = 'protectors';

    protected $fillable = [
        'blunt',
        'stab',
        'projectile',
        'fire',
    ];
}
