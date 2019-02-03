<?php

namespace App\Dungeon\Commands;

use App\User;

class RespawnCommand extends Command
{
    protected function run()
    {
        $query = implode(' ', array_slice($this->input_array, 1));
        $user = $this->entityFinder->find($query, $this->user);
        $respawn_self = false;

        if (!$user) {
            $user = User::where('name', 'like', '%' . $query . '%')->first();
        }

        if (!$user) {
            $user = $this->user;
            $respawn_self = true;
        }

        if (!$user->isDead()) {
            return $this->fail('Player isn\'t dead.');
        }

        $user->respawn()->save();

        $this->setMessage('User has respawned.');
    }
}
