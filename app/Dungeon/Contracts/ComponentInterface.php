<?php

namespace Dungeon\Contracts;

use Illuminate\Database\Eloquent\Relations\HasOne;

interface ComponentInterface
{
    public function entity(): HasOne;
}
