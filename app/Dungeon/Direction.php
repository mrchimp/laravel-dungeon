<?php

namespace Dungeon;

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

    public static function name($direction)
    {
        return self::NAMES[strtolower($direction)];
    }

    /**
     * Check if a direction string is valid
     *
     * @param string $direction
     *
     * @return boolean
     */
    public static function isValid($direction)
    {
        return in_array(strtolower($direction), self::ALL);
    }

    /**
     * Get an nice version of $direction
     *
     * @param string $direction
     *
     * @return null|string
     */
    public static function sanitize($direction)
    {
        if (!self::isValid($direction)) {
            return null;
        }

        return strtolower($direction);
    }
}
