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

        if (is_null($this->user->room)) {
            return 'You float in an endless void.';
        }

        return $this->user->room->getDescription();
    }
}