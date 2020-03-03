<?php

namespace Dungeon\Entities\Food;

use Dungeon\Entity;
use Dungeon\User;

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
        return array_merge(parent::getSerializable(), [
            'healing',
        ]);
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

        return $this;
    }

    public function getHealing()
    {
        return $this->healing;
    }

    public function getVerbs()
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'eat';

        return $verbs;
    }
}
