<?php

namespace Dungeon;

use Illuminate\Database\Eloquent\Collection;

class CurrentLocation
{
    protected ?User $user;

    protected ?Room $room;

    public function __construct(user $user)
    {
        $this->user = $user;
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

        if ($refresh) {
            $this->room->load('northExits', 'southExits', 'westExits', 'eastExits');
        }

        return (new Collection([
            'north' => $this->room->northExit,
            'south' => $this->room->southExit,
            'west' => $this->room->westExit,
            'east' => $this->room->eastExit,
        ]))->filter();
    }
}
