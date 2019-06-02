<?php

namespace Dungeon\Commands;

use Dungeon\Events\UserSaysToRoom;

class SayCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^say (?<message>.*)$/',
        ];
    }

    protected function run()
    {
        $message = $this->inputPart('message');

        $room = $this->user->getRoom();

        event(new UserSaysToRoom($room, $message, $this->user));
    }
}
