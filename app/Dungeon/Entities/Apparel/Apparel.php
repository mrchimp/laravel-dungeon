<?php

namespace Dungeon\Apparel;

use Dungeon\Entity;
use Dungeon\Traits\IsEquipable;

/**
 * A piece of clothing or armour that can be equipped
 * and may provide protection of different types
 */
class Apparel extends Entity
{
    use IsEquipable;

    /**
     * The name of this type of entity
     */
    public function getType(): string
    {
        return 'apparel';
    }

    /**
     * Whether this item can be equipped
     */
    public function isEquipable(): bool
    {
        return true;
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
        if (is_null($this->owner_id) && is_null($this->npc_id)) {
            throw new \Exception('Trying to wear an item that has no owner.');
        }

        $this->equiped = !!$wear;

        return $this;
    }

    /**
     * Verbs that this items supports
     *
     * @todo don't think this way of doing things is sustainable
     */
    public function getVerbs(): array
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'wear';

        return $verbs;
    }
}
