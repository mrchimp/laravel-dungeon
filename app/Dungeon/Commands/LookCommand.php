<?php

namespace App\Dungeon\Commands;

use App\Room;
use Auth;

/**
 * Look at the surrounding environment or a specific object
 */
class LookCommand extends Command
{
    public function patterns()
    {
        return [
            'look',
            'look at (.*)',
        ];
    }

    protected function run()
    {
        if (is_null($this->user->room)) {
            return $this->fail($this->current_location->getDescription());
        }

        $this->setMessage($this->current_location->getDescription());
    }
}
