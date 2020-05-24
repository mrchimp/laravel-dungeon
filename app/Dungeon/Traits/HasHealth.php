<?php

namespace Dungeon\Traits;

use Dungeon\Actions\Users\Expire;
use Dungeon\User;

trait HasHealth
{
    public $health = 100;

    public function hurt(int $amount, string $type): self
    {
        if ($this->isDead()) {
            return $this;
        }

        $this->health -= $amount;

        if ($this->isDead()) {
            $this->onDeath();
        }

        return $this;
    }

    public function heal(int $amount): self
    {
        if ($this->isDead()) {
            return $this;
        }

        $this->health += $amount;

        return $this;
    }

    public function setHealth(int $amount): self
    {
        $this->health = $amount;

        return $this;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function isDead(): bool
    {
        return $this->health <= 0;
    }

    /**
     * Determine if the body is alive
     *
     * @return bool
     */
    public function isAlive(): bool
    {
        return !$this->isDead();
    }

    public function onDeath(): void
    {
        //
    }
}
