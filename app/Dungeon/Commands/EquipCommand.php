<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;

class EquipCommand extends Command
{
    const EQUIPABLE_TYPES = [
        'apparel',
    ];

    public function run()
    {
        $query = implode(' ', array_slice($this->input_array, 1));;
        $finder = new Finder;
        $entity = $finder->find($query, $this->user);

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

        $this->setOutputItem('inventory', $this->user->getInventory(true));

        $entity->save();
    }
}