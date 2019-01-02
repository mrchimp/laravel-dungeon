<?php

namespace App\Dungeon\Commands;

use App\Room;

class LookCommand extends Command
{
    public function run(string $input)
    {
        $room = new Room([
            'description' => 'You are in a room.',
        ]);

        return $room->description;
    }
}