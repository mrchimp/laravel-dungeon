<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;

class DropCommand extends Command
{
    public function run()
    {
        $query = implode(' ', array_slice($this->input_array, 1));
        $finder = new Finder;
        $entity = $finder->findInInventory($query, $this->user);

        if (!$entity) {
            return 'You don\'t have a ' . $query;
        }


    }
}