<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Users\Say;
use Dungeon\Events\UserSaysToRoom;
use Dungeon\Exceptions\UserNotInRoomException;

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

        try {
            Say::do($this->user, $message);
        } catch (UserNotInRoomException $e) {
            return $this->fail('In the void, nobody can hear you speak.');
        }

        return $this;
    }
}
