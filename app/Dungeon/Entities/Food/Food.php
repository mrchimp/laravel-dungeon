<?php

namespace App\Dungeon\Entities\Food;

use App\Entity;

class Food extends Entity
{
    protected $serializable = [
        'healing',
    ];

    public $healing = 0;

    public function eat($consumer)
    {
        $consumer->heal($this->healing);
    }
}