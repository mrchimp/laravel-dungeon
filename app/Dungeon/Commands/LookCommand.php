<?php

namespace App\Dungeon\Commands;

use Auth;
use App\Room;

class LookCommand extends Command
{
    public function run(string $input)
    {
        if (is_null($this->user)) {
            return null;
        }

        $output = '';
        $output .= $this->getDescription();

        $exits = $this->getExits();

        if ($exits) {
            $output .= "\nExits: \n";
            
            foreach ($exits as $direction => $exit) {
                $output .= $direction . ': ' . $exit;
            }
        }

        return $output;
    }

    protected function getDescription()
    {
        if (is_null($this->user->room)) {
            return 'You float in an endless void.';
        }

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
}