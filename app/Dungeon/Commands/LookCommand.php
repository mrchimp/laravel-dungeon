<?php

namespace App\Dungeon\Commands;

use App\Room;
use Auth;

class LookCommand extends Command
{
    public function run()
    {
        if (is_null($this->user->room)) {
            return $this->fail($this->current_location->getDescription());
        }

        $this->setMessage($this->current_location->getDescription());
    }
}
