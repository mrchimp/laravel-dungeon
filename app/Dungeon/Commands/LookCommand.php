<?php

namespace App\Dungeon\Commands;

use App\Room;
use Auth;

class LookCommand extends Command
{
    public function run()
    {
        if (is_null($this->user->room)) {
            return 'You float in an endless void.';
        }

        $output = '';
        $output .= $this->getDescription();

        $exits = $this->getExits();

        if ($exits) {
            $output .= "<br>Exits: <br>";

            foreach ($exits as $direction => $exit) {
                $output .= e($direction) . ': ' . e($exit);
            }
        }

        $contents = $this->getContents();

        if ($contents) {
            $output .= '<br>There is:<br>' . $contents;
        }

        $players = $this->getPlayers();

        if ($players) {
            $output .= '<br>There are other people here:<br>' . $players;
        }

        $npcs = $this->getNPCs();

        if ($npcs) {
            $output .= '<br>There are some NPCs here: <br>' . $npcs;
        }

        return $output;
    }

    protected function getDescription()
    {
        return $this->user->room->getDescription();
    }

    protected function getExits()
    {
        if (is_null($this->user->room)) {
            return [];
        }

        $output = collect([
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

        return $output;
    }

    protected function getContents()
    {
        return $this->user->room->contents
            ->map(function ($entity) {
                return e($entity->getName()) . ' - ' . e($entity->getDescription());
            })
            ->implode('<br>');
    }

    protected function getPlayers()
    {
        return $this->user->room->people
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
        return $this->user->room->npcs
            ->map(function ($npc) {
                return $npc->getName();
            })
            ->implode('<br>');
    }
}