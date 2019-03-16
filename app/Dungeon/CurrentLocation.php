<?php

namespace Dungeon;

use Illuminate\Database\Eloquent\Collection;

class CurrentLocation
{
    protected $user;

    protected $room;

    public function __construct($user)
    {
        $this->user = $user;
        $this->room = $this->user->getRoom();
    }

    public function getRoom()
    {
        return $this->room;
    }

    public function getDescription()
    {
        if (is_null($this->room)) {
            return 'You float in an endless void.';
        }

        return $this->room->getDescription();
    }

    public function getPlayers($refresh = false, $exclude_self = true)
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

    public function getNPCs($refresh = false)
    {
        if (!$this->room) {
            return new Collection([]);
        }

        return $this->room->npcs();
    }

    public function getItems($refresh = false)
    {
        if (!$this->user->getRoom()) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->room->load('contents');
        }

        return $this->room->items();
    }

    public function getExits($refresh = false)
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
