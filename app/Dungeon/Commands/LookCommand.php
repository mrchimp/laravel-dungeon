<?php

namespace App\Dungeon\Commands;

use Auth;
use App\Room;

class LookCommand extends Command
{
    public function run(string $input)
    {
        // $room = new Room([
        //     'description' => 'You are in a room.',
        // ]);

        // $user = User::create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'password' => 'fakepassword',
        // ]);

        // return $room->getDescription();

        if (is_null($this->user)) {
            return null;
        }

        if (is_null($this->user->room)) {
            return 'You float in an endless void.';
        }

        return $this->user->room->getDescription();
    }
}