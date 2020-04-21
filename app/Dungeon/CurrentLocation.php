<?php

namespace Dungeon;

use Illuminate\Database\Eloquent\Collection;

/**
 * The location of the current user.
 *
 * Used as a convienience feature to get to other information.
 * It is also provided as sometimes (e.g. when the player is dead)
 * they won't be in a room so having this provides a buffer when
 * otherwise null would be returned.
 */
class CurrentLocation
{
    protected ?User $user;

    protected ?Room $room;

    public function __construct(user $user)
    {
        $this->user = $user;
        $this->room = $this->user->getRoom();
    }

    public function refresh(): void
    {
        $this->user->refresh();
        $this->room = $this->user->getRoom();
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function getDescription(): string
    {
        if (is_null($this->room)) {
            return 'You float in an endless void.';
        }

        return $this->room->getDescription();
    }

    public function getPlayers(bool $refresh = false, bool $exclude_self = true): Collection
    {
        if (!$this->room) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->room->load('contents');
        }

        return $this
            ->room
            ->people($this->user->getBody())
            ->values();
    }

    public function getNPCs(bool $refresh = false): Collection
    {
        if (!$this->room) {
            return new Collection([]);
        }

        return $this->room->npcs();
    }

    public function getItems(bool $refresh = false): Collection
    {
        if (!$this->user->getRoom()) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->room->load('contents');
        }

        return $this->room->items();
    }

    public function getExits(bool $refresh = false): Collection
    {
        if (is_null($this->user->getRoom())) {
            return new Collection([]);
        }

        // @todo find out why this fails completely D:
        // if ($refresh) {
        //     $this->room->load([
        //         'northPortals',
        //         'southPortals',
        //         'westPortals',
        //         'eastPortals',
        //     ]);
        // }

        return new Collection([
            'north' => $this->room->northPortal,
            'south' => $this->room->southPortal,
            'west' => $this->room->westPortal,
            'east' => $this->room->eastPortal,
        ]);
    }
}
