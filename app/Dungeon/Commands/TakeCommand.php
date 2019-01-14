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
            $this->setMessage('Take what?');
            return;
        }

        if ($entity->ownedBy($this->user)) {
            $this->setMessage('You already have that.');
            return;
        }

        $entity->giveToUser($this->user);
        $entity->save();

        $this->setMessage('You take the ' . e($entity->getName()) . '.');
    }
}