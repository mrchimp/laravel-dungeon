<?php

namespace App\Dungeon\Entities\Food;

use App\Entity;
use App\User;

class Food extends Entity
{
    public $healing = 0;

    protected $fillable = [
        'name',
        'description',
        'healing',
    ];

    public function getSerializable()
    {
        return [
            'healing',
        ];
    }

    public function getType()
    {
        return 'food';
    }

    public function eat(User $consumer)
    {
        $consumer->heal($this->healing);
    }

    public function setHealing($amount)
    {
        $this->healing = $amount;
    }

    public function getHealing()
    {
        return $this->healing;
    }
}