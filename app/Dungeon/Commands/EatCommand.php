<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Food\Eat;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\UnsupportedVerbException;

class EatCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^eat (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $entity = $this->entityFinder->find($this->inputPart('target'), $this->user);

        try {
            Eat::do($this->user, $entity);
        } catch (MissingEntityException $e) {
            return $this->fail('Could not find ' . e($this->inputPart('target')) . '.');
        } catch (UnsupportedVerbException $e) {
            return $this->fail('You can\'t eat that.');
        }

        $this->message = 'You eat the ' . e($entity->getName()) . '. ' .
            'It heals you for ' . $entity->getHealing() . '. ' .
            'Your health is now ' . $this->user->body->getHealth();

        return $this;
    }
}
