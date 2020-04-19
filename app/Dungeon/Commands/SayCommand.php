<?php

namespace Dungeon\Commands;

use Dungeon\Events\UserSaysToRoom;

class SayCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^say (?<message>.*)$/',
        ];
    }

    protected function run(): self
    {
        $message = $this->inputPart('message');

        $room = $this->user->getRoom();

        event(new UserSaysToRoom($room, $message, $this->user));

        return $this;
    }
}
