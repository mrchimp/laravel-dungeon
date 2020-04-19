<?php

namespace Dungeon;

use Illuminate\Support\Arr;

class Direction
{
    const NORTH = 'north';
    const SOUTH = 'south';
    const EAST = 'east';
    const WEST = 'west';

    const ALL = [
        self::NORTH,
        self::SOUTH,
        self::EAST,
        self::WEST,
    ];

    const NAMES = [
        self::NORTH => 'North',
        self::SOUTH => 'South',
        self::EAST => 'East',
        self::WEST => 'West',
    ];

    /**
     * Get the name of a direction
     */
    public static function name(string $direction): ?string
    {
        return Arr::get(self::NAMES, strtolower($direction));
    }

    /**
     * Check if a direction string is valid
     */
    public static function isValid(?string $direction): bool
    {
        return in_array(strtolower($direction), self::ALL);
    }

    /**
     * Get an nice version of $direction
     */
    public static function sanitize(?string $direction): ?string
    {
        if (!self::isValid($direction)) {
            return null;
        }

        return strtolower($direction);
    }
}
