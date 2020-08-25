<?php

namespace Dungeon\Entities\People;

use Dungeon\Actions\Users\Expire;
use Dungeon\Contracts\ContainsItems;
use Dungeon\Contracts\Damageable;
use Dungeon\Entity;
use Dungeon\NPC;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasHealth;
use Dungeon\Traits\HasInventory;
use Dungeon\User;

class Body extends Entity implements Damageable, ContainsItems
{
    use HasHealth,
        HasInventory,
        HasApparel;

    protected $table = 'entities';

    public function getSerializable(): array
    {
        return array_merge(parent::getSerializable(), [
            'health' => 100,
        ]);
    }

    public function ownerType(): ?string
    {
        if (!is_null($this->owner_id)) {
            return 'user';
        }

        if (!is_null($this->npc_id)) {
            return 'npc';
        }

        return null;
    }

    public function user(): void
    {
        throw new \Exception('You want to use the "owner" relationship, not "user".');
    }

    public function giveToUser(User $user = null): Body
    {
        if (is_null($user)) {
            $this->owner()->dissociate();
            $this->npc()->dissociate();
            return $this;
        }

        $this->name = $user->name;

        $this->npc()->dissociate();
        $this->owner()->associate($user);

        return $this;
    }

    public function giveToNPC(NPC $npc = null): Body
    {
        if (is_null($npc)) {
            $this->npc()->dissociate();
            $this->owner()->dissociate();
            return $this;
        }

        $this->name = $npc->name;

        $this->owner()->dissociate();
        $this->npc()->associate($npc);

        return $this;
    }

    public function canBeAttacked(): bool
    {
        if (!$this->owner) {
            return false;
        }

        if (!$this->owner->canBeAttacked()) {
            return false;
        }

        return true;
    }

    public function onDeath(): void
    {
        Expire::do($this);
    }

    public function canBeLooted(): bool
    {
        return $this->isDead();
    }
}
