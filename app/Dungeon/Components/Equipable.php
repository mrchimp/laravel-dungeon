<?php

namespace Dungeon\Components;

class Equipable extends Component
{
    protected $table = 'equipables';

    protected $fillable = [
        'is_equiped',
    ];

    /**
     * Has a user equiped this item
     */
    public function isEquiped(): bool
    {
        if (is_null($this->is_equiped)) {
            return false;
        }

        return $this->is_equiped;
    }

    public function equip(): self
    {
        $this->is_equiped = true;

        return $this;
    }
}
