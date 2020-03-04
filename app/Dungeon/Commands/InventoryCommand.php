<?php

namespace Dungeon\Commands;

class InventoryCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^inventory$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
    {
        $entities = $this->user->getInventory();

        if (count($entities) === 0) {
            $this->setMessage('You don\'t have anything.');
            return;
        }

        $this->setMessage('You have: <br>' .
            $entities
            ->map(function ($entity) {
                return e($entity->getName());
            })
            ->implode('<br>'));
    }
}
