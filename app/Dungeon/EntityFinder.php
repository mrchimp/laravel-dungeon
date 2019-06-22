<?php

namespace Dungeon;

use Dungeon\Room;
use Dungeon\User;

class EntityFinder
{
    /**
     * Find an entity in the user's current location
     *
     * Includes the user's inventory, the room, in
     * containers in the room, other users...
     *
     * @param string $query name to search for
     * @param User $user
     * @return Collection
     */
    public function find($query, $user)
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
     *
     * @param string $query
     * @param User $user
     * @return Collection
     */
    public function findInInventory($query, User $user)
    {
        return $user->inventory->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    /**
     * Find an Entity in the current room by name
     *
     * @param string $query
     * @param Room $room
     * @return Collection
     */
    public function findInRoom($query, $room)
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
     *
     * @param string $query
     * @param Room $room
     * @return Collection
     */
    public function findInContainersInRoom($query, $room)
    {
        if (!$room) {
            return null;
        }

        foreach ($room->contents as $container) {
            $entity = $container->findContents($query);

            if ($entity) {
                return $entity;
            }
        }
    }

    /**
     * Find users in the current room
     *
     * @param string $query
     * @param Room $room
     * @return User
     */
    public function findUsers($query, Room $room = null)
    {
        if (!$room) {
            return null;
        }

        return $room->contents->first(function ($person) use ($query) {
            return $person->nameMatchesQuery($query);
        });
        // return $room->people->first(function ($person) use ($query) {
        //     return $person->nameMatchesQuery($query);
        // });
    }
}
