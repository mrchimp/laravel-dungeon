<?php

namespace App\Dungeon\Commands;

class EquipCommand extends Command
{
    /**
     * Types of items that can be equipped
     *
     * @var array
     */
    const EQUIPABLE_TYPES = [
        'apparel',
    ];

    /**
     * Patterns that this command handles
     *
     * @return array
     */
    public function patterns()
    {
        return [
            '/^equip$/',
            '/^equip (?<target>.*)$/',
        ];
    }

    /**
     * Run the command
     *
     * @return null
     */
    protected function run()
    {
        $query = $this->inputPart('target');
        $entity = $this->entityFinder->find($query, $this->user);

        if (!$entity) {
            return $this->fail('Equip what?');
        }

        if (!$entity->ownedBy($this->user)) {
            return $this->fail('You don\'t have that.');
        }

        if (!in_array($entity->getType(), self::EQUIPABLE_TYPES)) {
            return $this->fail('You can\'t equip that.');
        }

        if ($entity->isEquiped()) {
            $entity->unequip();
            $this->setMessage('Equipped.');
        } else {
            $entity->equip();
            $this->setMessage('Unequipped.');
        }

        $entity->save();
    }
}
