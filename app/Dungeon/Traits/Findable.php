<?php

namespace Dungeon\Traits;

use Illuminate\Support\Str;

trait Findable
{
    public function nameMatchesQuery($query)
    {
        return Str::contains(
            strtolower($this->name),
            strtolower($query)
        );
    }
}
