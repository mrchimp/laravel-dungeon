<?php

namespace Dungeon\Commands;

class InventoryCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^inventory$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $entities = $this->user->getInventory();

        if (count($entities) === 0) {
            $this->setMessage('You don\'t have anything.');

            return $this;
        }

        $this->setMessage('You have: <br>' .
            $entities
            ->map(function ($entity) {
                return e($entity->getName());
            })
            ->implode('<br>'));

        return $this;
    }
}
