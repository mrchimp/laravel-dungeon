<?php

namespace App\Dungeon\Commands;

class TakeCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^take$/',
            '/^take (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
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

        $entity->giveToUser($this->user);
        $entity->save();

        $this->setMessage('You take the ' . e($entity->getName()) . '.');
    }
}
