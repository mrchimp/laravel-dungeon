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
            return $this->fail('Take what?');
        }

        if ($entity->ownedBy($this->user)) {
            return $this->fail('You already have that.');
        }

        $entity->giveToUser($this->user);
        $entity->save();

        $this->setMessage('You take the ' . e($entity->getName()) . '.');

        $this->setOutputItem('items', $this->current_location->getItems(true));
        $this->setOutputItem('inventory', $this->user->getInventory(true));
    }
}