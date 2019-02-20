<?php

namespace Dungeon\Traits;

trait HasHealth
{
    public $health = 100;

    public function hurt($amount)
    {
        $this->health -= $amount;

        if ($this->health <= 0) {
            $this->kill();
        }

        return $this;
    }

    public function heal($amount)
    {
        $this->health += $amount;

        if ($this->health <= 0) {
            $this->kill();
        }

        return $this;
    }

    public function setHealth($amount)
    {
        $this->health = $amount;

        if ($this->health <= 0) {
            $this->kill();
        }

        return $this;
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
