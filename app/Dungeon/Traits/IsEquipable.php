<?php

namespace Dungeon\Traits;

use Dungeon\Apparel\Apparel;
use Dungeon\Entities\People\Body;

trait IsEquipable
{
    public function equip()
    {
        $this->equiped = true;

        return $this;
    }

    public function unequip()
    {
        $this->equiped = false;

        return $this;
    }

    public function isEquiped(): bool
    {
        return $this->equiped;
    }

    /**
     * Whether this item is currently being worn
     */
    public function isWorn(): bool
    {
        return $this->isEquiped();
    }

    /**
     * Put this item of apparel on
     */
    public function wear(bool $wear = true): Apparel
    {
        if (!($this->container instanceof Body)) {
            throw new \Exception('Trying to wear an item that does not belogn to a Body.');
        }

        $this->equiped = !!$wear;

        return $this;
    }
}
