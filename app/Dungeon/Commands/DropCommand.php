<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Entities\Drop;
use Dungeon\Exceptions\MissingEntityException;

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

        try {
            $action = Drop::do($this->user, $entity);
        } catch (MissingEntityException $e) {
            return $this->fail('You don\'t have a ' . e($query));
        }

        if ($action->failed()) {
            return $this->fail($action->getMessage());
        }

        $this->setMessage($action->getMessage());

        return $this;
    }
}
