<?php

namespace Dungeon\Commands;

use App\User;

class RespawnCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^respawn$/',
            '/^respawn (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
    {
        $target_name = $this->inputPart('target');

        if (!$target_name) {
            $target = $this->user;
        } else {
            // @todo - this is pointless cos they'll be dead...
            // should add a method to the finder for finding dead people
            $target = $this->entityFinder->find($target_name, $this->user);

            if (!$target) {
                $target = User::where('name', 'like', '%' . $target_name . '%')->first();
            }
        }

        if (!$target->isDead()) {
            return $this->fail('Player isn\'t dead.');
        }

        $target->respawn()->save();

        $this->setMessage('User has respawned.');
    }
}
