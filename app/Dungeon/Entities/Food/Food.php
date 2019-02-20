<?php

namespace Dungeon\Entities\Food;

use Dungeon\Entity;
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

    public function getVerbs()
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'eat';

        return $verbs;
    }
}
