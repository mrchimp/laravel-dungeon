<?php

namespace Dungeon\Entities;

class ItemFinder
{
    public function find($query, $user)
    {
        $entity = $this->findInInventory($query, $user);

        if (!$entity) {
            $entity = $this->findInRoom($query, $user->room);
        }

        if (!$entity) {
            $entity = $this->findInContainersInRoom($query, $user->room);
        }

        if (!$entity) {
            $entity = $this->findUsers($query, $user->room);
        }

        if (!$entity) {
            return null;
        }

        return $entity::replaceClass($entity);
    }

    public function findInInventory($query, $user)
    {
        return $user->inventory->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    public function findInRoom($query, $room)
    {
        if (!$room) {
            return null;
        }

        return $room->contents->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

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

    public function findUsers($query, $room)
    {
        if (!$room) {
            return null;
        }

        return $room->people->first(function ($person) use ($query) {
            return $person->nameMatchesQuery($query);
        });
    }
}
