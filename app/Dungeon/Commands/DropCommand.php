<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;

class DropCommand extends Command
{
    public function run()
    {
        $finder = new Finder;
        $entity = $finder->findInInventory($this->query, $this->user);

        if (!$entity) {
            return 'You don\'t have a ' . e($this->query);
        }

        $entity->moveToRoom($this->user->room);
        $entity->save();

        return 'You drop the ' . e($entity->getName()) . '.';
    }
}