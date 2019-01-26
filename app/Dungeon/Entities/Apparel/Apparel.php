<?php

namespace App\Dungeon\Apparel;

use App\Dungeon\Traits\IsEquipable;
use App\Entity;

class Apparel extends Entity
{
    use IsEquipable;

    public function getType()
    {
        return 'apparel';
    }

    public function isWorn()
    {
        return $this->isEquiped();
    }

    public function wear($wear = true)
    {
        if (is_null($this->owner_id) && is_null($this->npc_id)) {
            throw new \Exception('Trying to wear an item that has no owner.');
        }

        $this->equiped = !!$wear;

        return $this;
    }

    public function getVerbs()
    {
        $verbs = parent::getVerbs();

        $verbs[] = 'wear';

        return $verbs;
    }
}
