<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Users\Respawn;
use Dungeon\Exceptions\UserIsAliveException;
use Dungeon\User;

class RespawnCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^respawn$/',
            '/^respawn (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $target_name = $this->inputPart('target');

        if (!$target_name) {
            $target = $this->user;
        } else {
            $target = User::where('name', 'like', '%' . $target_name . '%')->first();
        }

        try {
            Respawn::do($target);
        } catch (UserIsAliveException $e) {
            return $this->fail('You\'re already alive!');
        }

        $this->setMessage('You wake up.');

        return $this;
    }
}
