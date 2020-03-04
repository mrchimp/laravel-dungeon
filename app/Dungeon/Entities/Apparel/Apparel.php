<?php

namespace Dungeon\Apparel;

use Dungeon\Contracts\ApparelInterface;
use Dungeon\Entity;
use Dungeon\Traits\IsEquipable;

/**
 * A piece of clothing or armour that can be equipped
 * and may provide protection of different types
 */
class Apparel extends Entity implements ApparelInterface
{
    use IsEquipable;

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
