<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;


class KillCommand extends Command
{
    public function run()
    {
        $finder = new Finder;
        $query = implode(' ', array_slice($this->input_array, 1));
        $user = $finder->find($query, $this->user);

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
