<?php

namespace Dungeon\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface ComponentInterface
{
    public function entity(): BelongsTo;
}