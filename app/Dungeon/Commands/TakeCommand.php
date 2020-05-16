<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Entities\Take;
use Dungeon\Exceptions\EntityPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\UntakeableEntityException;

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
        $query = $this->inputPart('target');
        $entity = $this->entityFinder->find($query, $this->user);

        try {
            Take::do($this->user, $entity);
        } catch (MissingEntityException $e) {
            return $this->fail('Take what?');
        } catch (EntityPossessedException $e) {
            return $this->fail('You already have that.');
        } catch (UntakeableEntityException $e) {
            return $this->fail('You cannot take that.');
        }

        $this->setMessage('You take the ' . e($entity->getName()) . '.');

        return $this;
    }
}
