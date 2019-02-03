<?php

namespace App\Dungeon\Commands;

class DropCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^drop (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
    {
        $query = $this->inputPart('target');
        $entity = $this->entityFinder->findInInventory($query, $this->user);

        if (!$entity) {
            return $this->fail('You don\'t have a ' . e($query));
        }

        $entity->moveToRoom($this->user->room);
        $entity->save();

        $this->setMessage('You drop the ' . e($entity->getName()) . '.');
    }
}
