<?php

namespace Dungeon\Contracts;

use Dungeon\Apparel\Apparel;

interface ApparelInterface
{
    public function isEquiped(): bool;
    public function equip();
    public function unequip();
    public function isWorn(): bool;
    public function wear(bool $wear = true): Apparel;
}
