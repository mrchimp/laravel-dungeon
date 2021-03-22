<?php

namespace Dungeon\Components;

class Takeable extends Component
{
    protected $table = 'takeables';

    protected $fillable = [
        'weight',
    ];
}
