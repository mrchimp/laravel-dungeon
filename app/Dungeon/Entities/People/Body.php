<?php

namespace Dungeon\Entities\People;

use Dungeon\Entity;
use Dungeon\NPC;
use Dungeon\Room;
use Dungeon\Traits\HasApparel;
use Dungeon\Traits\HasHealth;
use Dungeon\Traits\HasInventory;
use Dungeon\User;

class Body extends Entity
{
    use HasHealth,
        HasInventory,
        HasApparel;

    protected $table = 'entities';

    public function getSerializable(): array
    {
        return array_merge(parent::getSerializable(), [
            'health',
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

    public function user()
    {
        throw new \Exception('You want to use the "owner" relationship, not "user".');
    }

    public function giveToUser(User $user = null): Body
    {
        $this->npc()->dissociate();

        if (is_null($user)) {
            $this->owner()->dissociate();
            return $this;
        }

        $this->name = $user->name;

        $this->owner()->associate($user);

        return $this;
    }

    public function giveToNPC(NPC $npc = null): Body
    {
        $this->owner()->dissociate();

        if (is_null($npc)) {
            $this->npc()->dissociate();
            return $this;
        }

        $this->name = $npc->name;

        $this->npc()->associate($npc);

        return $this;
    }

    public function moveToRoom(Room $room = null): Body
    {
        $this->room()->associate($room);

        return $this;
    }

    public function kill(): void
    {
        $this->room_id = null;
        $this->giveToUser(null);
        $this->save();
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

    /**
     * Determine if the body is alive
     *
     * @return boolean
     */
    public function isAlive(): bool
    {
        return $this->health > 0;
    }
}
