<?php

namespace App\Dungeon\Commands;

use Auth;
use App\Room;

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
                $output .= $direction . ': ' . $exit;
            }
        }

        $contents = $this->getContents();

        if ($contents) {
            $output .= '<br>There is:<br>' . $contents;
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
                return $room->portal->getDescription();
            })
            ->toArray();

        return $output;
    }

    protected function getContents()
    {
        return $this->user->room->contents
            ->map(function ($entity) {
                return $entity->getName() . ' - ' . $entity->getDescription();
            })
            ->implode('<br>');
    }
}