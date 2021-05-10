<?php

namespace Dungeon\Components;

use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipable extends Component
{
    protected $table = 'equipables';

    protected $fillable = [
        'is_equiped',
    ];

    public function entity(): HasOne
    {
        return $this->hasOne(Entity::class, 'equipable_id');
    }

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
