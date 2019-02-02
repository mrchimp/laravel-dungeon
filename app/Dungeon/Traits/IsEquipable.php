<?php

namespace App\Dungeon\Traits;

trait IsEquipable
{
    public function equip()
    {
        $this->equiped = true;
    }

    public function unequip()
    {
        $this->equiped = false;
    }

    public function isEquiped()
    {
        return $this->equiped;
    }
}
