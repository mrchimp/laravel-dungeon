<?php

namespace Dungeon;

use Dungeon\Contracts\ContainsItems;
use Dungeon\Contracts\WeaponInterface;
use Dungeon\Entities\People\Body;
use Dungeon\Entities\Weapons\Weapon;
use Dungeon\Room;
use Dungeon\User;
use Mockery\Matcher\Contains;

class EntityFinder
{
    /**
     * Find an entity in the user's current location
     *
     * Includes the user's inventory, the room, in
     * containers in the room, other users...
     */
    public function find(string $query, User $user): ?Entity
    {
        $entity = $this->findInInventory($query, $user);

        if (!$entity) {
            $entity = $this->findInRoom($query, $user->getRoom());
        }

        if (!$entity) {
            $entity = $this->findInContainersInRoom($query, $user->getRoom());
        }

        if (!$entity) {
            $entity = $this->findUsers($query, $user->getRoom());
        }

        if (!$entity) {
            return null;
        }

        return $entity::replaceClass($entity);
    }

    /**
     * Find an item in the user's inventory
     */
    public function findInInventory(string $query, User $user): ?Entity
    {
        if (!$user->hasBody()) {
            return null;
        }

        return $user->body->inventory->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    /**
     * Find a weapon in an inventory
     */
    public function findWeaponInInventory(string $query, User $user): ?Weapon
    {
        if (!$user->hasBody()) {
            return null;
        }

        return $user->body->inventory->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query) && $entity instanceof WeaponInterface;
        });
    }

    /**
     * Find an Entity in the current room by name
     */
    public function findInRoom(string $query, ?Room $room): ?Entity
    {
        if (!$room) {
            return null;
        }

        return $room->contents->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    /**
     * Find an entity in containers in the current room
     */
    public function findInContainersInRoom(string $query, ?Room $room): ?Entity
    {
        if (!$room) {
            return null;
        }

        foreach ($room->contents as $container) {
            if (!$container instanceof ContainsItems) {
                continue;
            }

            $entity = $container->findContents($query);

            if ($entity) {
                return $entity;
            }
        }

        return null;
    }

    /**
     * Find users in the current room
     */
    public function findUsers(string $query, ?Room $room = null): ?Body
    {
        if (!$room) {
            return null;
        }

        return $room->contents->first(function ($person) use ($query) {
            return $person->nameMatchesQuery($query);
        });
    }
}
