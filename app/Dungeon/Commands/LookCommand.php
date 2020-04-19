<?php

namespace Dungeon\Commands;

/**
 * Look at the surrounding environment or a specific object
 */
class LookCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^look$/',
            '/^look at (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        if (is_null($this->user->getRoom())) {
            return $this->fail($this->current_location->getDescription());
        }

        $target = $this->inputPart('target');

        if ($target) {
            $entity = $this->entityFinder->find($target, $this->user);

            if (!$entity) {
                return $this->fail('Can\'t see that.');
            }

            return $this->setMessage($entity->getDescription());
        }

        $this->setMessage($this->current_location->getDescription());

        return $this;
    }
}
