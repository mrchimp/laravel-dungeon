<?php

namespace App\Dungeon\Commands;

class InventoryCommand extends Command
{
    public function run()
    {
        $entities = $this->user->inventory;

        if (count($entities) === 0) {
            return 'You don\'t have anything.';
        }

        return 'You have: <br>' .
            $entities
            ->map(function($entity) {
                return $entity->getName();
            })
            ->implode('<br>');
    }
}