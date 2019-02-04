<?php

namespace App\Dungeon\Apparel;

use App\Dungeon\Traits\IsEquipable;
use App\Entity;

/**
 * A piece of clothing or armour that can be equipped
 * and may provide protection of different types
 */
class Apparel extends Entity
{
    use IsEquipable;

    /**
     * The name of this type of entity
     *
     * @return string
     */
    public function getType()
    {
        return 'apparel';
    }

    /**
     * Whether this item can be equipped
     *
     * @return boolean
     */
    public function isEquipable()
    {
        return true;
    }

    /**
     * Whether this item is currently being worn
     *
     * @return boolean
     */
    public function isWorn()
    {
        return $this->isEquiped();
    }

    /**
     * Put this item of apparel on
     *
     * @param boolean $wear
     * @return self
     */
    public function wear($wear = true)
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
     *
     * @return void
     */
    public function getVerbs()
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'wear';

        return $verbs;
    }
}
