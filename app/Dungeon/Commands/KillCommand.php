<?php

namespace App\Dungeon\Commands;

class KillCommand extends Command
{
    protected function run()
    {
        $query = implode(' ', array_slice($this->input_array, 1));
        $user = $this->entityFinder->find($query, $this->user);

        if (!$user) {
            return $this->fail('Kill who?');
        }

        // @todo inefficient
        foreach ($user->inventory as $item) {
            $item->moveToRoom($user->room)->save();
        }

        $user->setHealth(0)->save();

        $this->setMessage('Deaded.');
    }
}
