<?php

namespace Dungeon\Traits;

use Illuminate\Support\Str;

trait Findable
{
    public function nameMatchesQuery($query)
    {
        $query = trim($query);

        $matches = [];

        preg_match('/^(?:the )?(.*)$/', $query, $matches);

        if (empty($matches)) {
            return false;
        }

        return Str::contains(
            strtolower($this->name),
            strtolower($matches[1])
        );
    }
}
