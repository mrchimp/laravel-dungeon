<?php

namespace App\Dungeon\Traits;

trait Findable
{
    public function nameMatchesQuery($query)
    {
        return str_contains(
            strtolower($this->name),
            strtolower($query)
        );
    }
}