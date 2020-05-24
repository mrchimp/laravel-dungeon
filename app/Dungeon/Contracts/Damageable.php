<?php

namespace Dungeon\Contracts;

interface Damageable
{
    /**
     * Deal a given amount of a given damage type
     */
    public function hurt(int $amount, string $type): self;

    /**
     * Restore a given amount of damage
     */
    public function heal(int $amount): self;

    /**
     * Set the Entity's health
     */
    public function setHealth(int $amount): self;

    /**
     * Get the Entity's health
     */
    public function getHealth(): int;


    /**
     * Whether or not the Entity is dead
     */
    public function isDead(): bool;

    /**
     * Whether or not the Entity is still alive.
     *
     * The inverse of isDead
     */
    public function isAlive(): bool;

    /**
     * A callback method for when the item is killed
     */
    public function onDeath(): void;
}
