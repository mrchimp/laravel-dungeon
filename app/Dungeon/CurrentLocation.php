<?php

namespace Dungeon;

use Illuminate\Database\Eloquent\Collection;

class CurrentLocation
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getDescription()
    {
        if (is_null($this->user->room)) {
            return 'You float in an endless void.';
        }

        return $this->user->room->getDescription();
    }

    public function getPlayers($refresh = false, $exclude_self = true)
    {
        if (!$this->user->room) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->user->room->load('people');
        }

        return $this->user->room->people
            ->filter(function ($user) use ($exclude_self) {
                if (!$exclude_self) {
                    return true;
                }
                return $user->id !== $this->user->id;
            })
            ->values();
    }

    public function getNPCs($refresh = false)
    {
        if (!$this->user->room) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->user->room->load('npcs');
        }

        return $this->user->room->npcs;
    }

    public function getItems($refresh = false)
    {
        if (!$this->user->room) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->user->room->load('contents');
        }

        return $this->user->room->contents;
    }

    public function getExits($refresh = false)
    {
        if (is_null($this->user->room)) {
            return new Collection([]);
        }

        if ($refresh) {
            $this->user->room->load('northExits', 'southExits', 'westExits', 'eastExits');
        }

        return (new Collection([
            'north' => $this->user->room->northExit,
            'south' => $this->user->room->southExit,
            'west' => $this->user->room->westExit,
            'east' => $this->user->room->eastExit,
        ]))->filter();
    }
}
