<?php

namespace App\Dungeon\Traits;

trait HasHealth
{
    public $health = 100;

    public function hurt($amount)
    {
        $this->health -= $amount;
    }

    public function heal($amount)
    {
        $this->health += $amount;
    }

    public function getHealth()
    {
        return $this->health;
    }

    public function isDead()
    {
        return $this->health <= 0;
    }
}