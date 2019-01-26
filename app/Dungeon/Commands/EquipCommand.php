<?php

namespace App\Dungeon\Commands;

use App\Dungeon\Entities\Finder;

class EquipCommand extends Command
{
    public function run()
    {
        $query = implode(' ', array_slice($this->input_array, 1));;
        $finder = new Finder;
        $entity = $finder->find($query, $this->user);

        if (!$entity) {
            $this->setMessage('Equip what?');
            return;
        }

        if (!$entity->ownedBy($this->user)) {
            $this->setMessage('You don\'t have that.');
            return;
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