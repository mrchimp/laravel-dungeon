<?php

namespace App\Dungeon\Traits;

use App\Entity;

trait HasApparel
{
    public function getApparel()
    {
        return $this
            ->inventory
            ->filter(function ($apparel) {
                return $apparel->isEquiped() && $apparel->getType() === 'apparel';
            });
    }

    public function wear(Apparel $apparel)
    {
        $apparel->giveToUser($apparel);
        $apparel->equip();

        return $this;
    }
}
