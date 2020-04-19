<?php

namespace Dungeon\Commands;

class DropCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^drop (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $query = $this->inputPart('target');
        $entity = $this->entityFinder->findInInventory($query, $this->user);

        if (!$entity) {
            return $this->fail('You don\'t have a ' . e($query));
        }

        $entity->moveToRoom($this->current_location->getRoom());
        $entity->save();

        $this->setMessage('You drop the ' . e($entity->getName()) . '.');

        return $this;
    }
}
