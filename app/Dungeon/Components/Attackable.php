<?php

namespace Dungeon\Components;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Attackable extends Component
{
    protected $table = 'attackables';

    protected $fillable = [
        'hp',
        'blunt',
        'stab',
        'projectile',
        'fire',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class, 'attackable_id');
    }

    public function attack($amount = 0, $type = 'blunt'): self
    {
        if ($this->isDead()) {
            return $this;
        }

        $this->hp -= $amount;
        $this->save();

        return $this;
    }

    public function setHealth(int $amount): self
    {
        $this->hp = $amount;

        return $this;
    }

    public function getHealth(): int
    {
        return $this->hp;
    }

    public function isDead(): bool
    {
        return $this->hp <= 0;
    }

    public function isAlive(): bool
    {
        return !$this->isDead();
    }
}
