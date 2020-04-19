<?php

namespace Dungeon\Commands;

class TakeCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^take$/',
            '/^take (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        // $query = implode(' ', array_slice($this->input_array, 1));
        $query = $this->inputPart('target');
        $entity = $this->entityFinder->find($query, $this->user);

        if (!$entity) {
            return $this->fail('Take what?');
        }

        if ($entity->ownedBy($this->user)) {
            return $this->fail('You already have that.');
        }

        if (!$entity->canBeTaken()) {
            return $this->fail('You cannot take that.');
        }

        $entity->giveToUser($this->user);
        $entity->save();

        $this->setMessage('You take the ' . e($entity->getName()) . '.');

        return $this;
    }
}
