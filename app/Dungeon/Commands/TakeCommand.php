<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Entities\Take;
use Dungeon\Exceptions\EntityPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\UntakeableEntityException;
use Dungeon\Exceptions\UserIsDeadException;

class TakeCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^take$/',
            '/^take (?<target>.*) from (?<container>.*)$/',
            '/^take (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        $container_name = $this->inputPart('container');
        $target = $this->inputPart('target');
        $container = null;

        if ($container_name) {
            $entity = $this->entityFinder->findInContainersInRoom($target, $this->current_location->getRoom());
        } else {
            $entity = $this->entityFinder->find($target, $this->user);
        }

        try {
            Take::do($this->user, $entity, $container);
        } catch (MissingEntityException $e) {
            return $this->fail('Take what?');
        } catch (EntityPossessedException $e) {
            return $this->fail('You already have that.');
        } catch (UntakeableEntityException $e) {
            return $this->fail('You cannot take that.');
        } catch (UserIsDeadException $e) {
            return $this->fail('You appear to be dead.');
        }

        $this->setMessage('You take the ' . e($entity->getName()) . '.');

        return $this;
    }
}
