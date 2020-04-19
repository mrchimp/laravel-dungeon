<?php

namespace Dungeon\Commands;

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

        if (!$target->isDead()) {
            return $this->fail('Player isn\'t dead.');
        }

        $target->respawn()->save();

        $this->setMessage('User has respawned.');

        return $this;
    }
}
