<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;
use App\User;

class RespawnCommand extends Command
{
    public function run()
    {
        $finder = new Finder;
        $query = implode(' ', array_slice($this->input_array, 1));
        $user = $finder->find($query, $this->user);
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

        $user->respawn();

        $this->setMessage('User has respawned.');
    }
}