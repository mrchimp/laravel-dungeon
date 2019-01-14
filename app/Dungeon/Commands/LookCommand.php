<?php

namespace App\Dungeon\Commands;

use App\Room;
use Auth;

class LookCommand extends Command
{
    protected $exits = [];

    protected $items = [];

    protected $players = [];

    protected $npcs = [];

    protected $description = '';

    public function run()
    {
        if (is_null($this->user->room)) {
            $this->setMessage('You float in an endless void.');
            return;
        }

        $this->getExits();
        $this->getDescription();
        $this->getItems();
        $this->getPlayers();
        $this->getNpcs();

        $this->setOutputItem('exits', $this->exits);
        $this->setOutputItem('items', $this->items);
        $this->setOutputItem('players', $this->players);
        $this->setOutputItem('npcs', $this->npcs);
        $this->setOutputItem('description', $this->description);

        $output = '';
        $output .= $this->description;

        if ($this->exits) {
            $output .= "<br>Exits: <br>";

            foreach ($this->exits as $direction => $exit) {
                $output .= e($direction) . ': ' . e($exit);
            }
        }

        if ($this->items) {
            $output .= '<br>There is:<br>' . $this->items;
        }

        if ($this->players) {
            $output .= '<br>There are other people here:<br>' . $this->players;
        }

        if ($this->npcs) {
            $output .= '<br>There are some NPCs here: <br>' . $this->npcs;
        }

        $this->setMessage($output);
    }

    protected function getDescription()
    {
        $this->description = $this->user->room->getDescription();
    }

    protected function getExits()
    {
        if (is_null($this->user->room)) {
            $this->exits = [];
            return;
        }

        $this->exits = collect([
            'north' => $this->user->room->northExit,
            'south' => $this->user->room->southExit,
            'west' => $this->user->room->westExit,
            'east' => $this->user->room->eastExit,
        ])
            ->filter()
            ->map(function ($room) {
                return e($room->portal->getDescription());
            })
            ->toArray();
    }

    protected function getitems()
    {
        $this->items = $this->user->room->contents
            ->map(function ($entity) {
                return e($entity->getName()) . ' - ' . e($entity->getDescription());
            })
            ->implode('<br>');
    }

    protected function getPlayers()
    {
        $this->players = $this->user->room->people
            ->filter(function ($user) {
                return $user->id !== $this->user->id;
            })
            ->map(function ($player) {
                return e($player->name);
            })
            ->implode('<br>');
    }

    protected function getNPCs()
    {
        $this->npcs = $this->user->room->npcs
            ->map(function ($npc) {
                return $npc->getName();
            })
            ->implode('<br>');
    }
}