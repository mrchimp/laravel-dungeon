<?php

namespace Dungeon\Traits;

use Dungeon\Apparel\Apparel;
use Dungeon\Contracts\ApparelInterface;

trait HasApparel
{
    public function getApparel()
    {
        return $this
            ->getInventory()
            ->filter(function ($apparel) {
                if (!($apparel instanceof ApparelInterface)) {
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
