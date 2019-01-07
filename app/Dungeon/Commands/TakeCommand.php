<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;

class TakeCommand extends Command
{
    public function run()
    {
        $query = implode(' ', array_slice($this->input_array, 1));
        $finder = new Finder;
        $entity = $finder->find($query, $this->user);

        if (!$entity) {
            return 'Take what?';
        }

        if ($entity->ownedBy($this->user)) {
            return 'You already have that.';
        }

        $entity->giveToUser($this->user);
        $entity->save();

        return 'You take the ' . $entity->getName() . '.';
    }
}