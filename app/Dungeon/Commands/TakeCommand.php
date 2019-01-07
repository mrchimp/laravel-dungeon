<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;

class TakeCommand extends Command
{
    public function run(string $input)
    {
        $chunks = explode(' ', $input);
        $query = implode(' ', array_slice($chunks, 1));
        $finder = new Finder;
        $entity = $finder->find($query, $this->user);

        if ($entity->ownedBy($this->user)) {
            return 'You already have that.';
        }

        $entity->giveToUser($this->user);
        $entity->save();

        return 'You take the ' . $entity->getName() . '.';
    }
}