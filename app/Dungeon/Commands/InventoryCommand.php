<?php

namespace App\Dungeon\Commands;

class InventoryCommand extends Command
{
    public function run()
    {
        $entities = $this->user->inventory;

        if (count($entities) === 0) {
            $this->setMessage('You don\'t have anything.');
            return;
        }

        $this->setMessage('You have: <br>' .
            $entities
            ->map(function($entity) {
                return e($entity->getName());
            })
            ->implode('<br>'));
    }
}