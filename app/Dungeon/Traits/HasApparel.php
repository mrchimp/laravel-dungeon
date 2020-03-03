<?php

namespace Dungeon\Traits;

use Dungeon\Apparel\Apparel;

trait HasApparel
{
    public function getApparel()
    {
        return $this
            ->inventory
            ->filter(function ($apparel) {
                if ($apparel->getType() !== 'apparel') {
                    return false;
                }

                return $apparel->isEquiped();
            });
    }

    public function wear(Apparel $apparel)
    {
        $apparel->giveToUser($this);
        $apparel->equip();

        return $this;
    }
}
