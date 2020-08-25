<?php

namespace Dungeon\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface ContainsItems
{
    public function inventory(): HasMany;

    public function canBeLooted(): bool;
}
