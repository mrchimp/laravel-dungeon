<?php

namespace Dungeon\Commands;

use Dungeon\Actions\Apparel\Equip;
use Dungeon\Exceptions\EntityNotEquipableException;
use Dungeon\Exceptions\EntityNotPossessedException;
use Dungeon\Exceptions\MissingEntityException;

class EquipCommand extends Command
{
    /**
     * Patterns that this command handles
     */
    public function patterns(): array
    {
        return [
            '/^equip$/',
            '/^equip (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     */
    protected function run(): self
    {
        if (!$this->user->getRoom()) {
            return $this->fail('You float in an endless void.');
        }

        $query = $this->inputPart('target');
        $entity = $this->entityFinder->find($query, $this->user);

        try {
            Equip::do($this->user, $entity);
        } catch (MissingEntityException $e) {
            return $this->fail('Equip what?');
        } catch (EntityNotPossessedException $e) {
            return $this->fail('You don\'t have that.');
        } catch (EntityNotEquipableException $e) {
            return $this->fail('You can\'t equip that.');
        }

        if ($entity->equipable->isEquiped()) {
            $this->setMessage('Equiped.');
        } else {
            $this->setMessage('Unequipped.');
        }

        return $this;
    }
}
