<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Food\Food;

class EatCommand extends Command
{
    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^eat (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * Note: after running this command, the model
     * may still be loaded as a relationship. You
     * will need to reload the relationship in
     * order for it to be removed.
     *
     * @return null
     */
    protected function run()
    {
        $entity = $this->entityFinder->find($this->inputPart('target'), $this->user);

        if (!$entity) {
            return $this->fail('Could not find ' . e($this->inputPart('target')) . '.');
        }

        if (!$entity->supportsVerb('eat')) {
            return $this->fail('You can\'t eat that.');
        }

        $entity->eat($this->user);
        $this->user->save();

        $message = 'You eat the ' . e($entity->getName()) . '. '.
            'It heals you for ' . $entity->getHealing() . '. ' .
            'Your health is now ' . $this->user->getHealth();

        $entity->delete();

        $this->setMessage($message);
    }
}
