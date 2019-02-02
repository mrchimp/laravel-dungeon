<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;
use App\Dungeon\Entities\Food\Food;

class EatCommand extends Command
{
    /**
     * Note: after running this command, the model
     * may still be loaded as a relationship. You
     * will need to reload the relationship in
     * order for it to be removed.
     */
    protected function run()
    {
        $finder = new Finder();
        $entity = $finder->find($this->query, $this->user);

        if (!$entity) {
            return $this->fail('Could not find ' . e($this->query) . '.');
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
