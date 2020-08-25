<?php

namespace Dungeon\Entities\Containers;

use Dungeon\Contracts\ContainsItems;
use Dungeon\Entity;
use Dungeon\Traits\HasInventory;

class Box extends Entity implements ContainsItems
{
    use HasInventory;
}
