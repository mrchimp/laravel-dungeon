<?php

namespace Dungeon\Entities\Food;

use Dungeon\Entity;
use Dungeon\User;

class Food extends Entity
{
    public $healing = 25;

    protected $fillable = [
        'name',
        'description',
        'healing',
    ];

    public function getSerializable(): array
    {
        return array_merge(parent::getSerializable(), [
            'healing' => 25,
        ]);
    }

    public function eat(User $consumer): void
    {
        $consumer->heal($this->healing);
    }

    public function setHealing($amount): self
    {
        $this->healing = $amount;

        return $this;
    }

    public function getHealing(): int
    {
        return $this->healing;
    }

    public function getVerbs(): array
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'eat';

        return $verbs;
    }
}
