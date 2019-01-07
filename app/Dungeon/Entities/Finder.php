<?php

namespace App\Dungeon\Entities;

use App\User;

class Finder
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
            return null;
        }

        return $entity;
    }

    public function findInInventory($query, $user)
    {
        return $user->inventory->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    public function findInRoom($query, $room)
    {
        return $room->contents->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }

    public function findInContainersInRoom($query, $room)
    {
        foreach ($room->contents as $container) {
            $entity = $container->findContents($query);

            if ($entity) {
                return $entity;
            }
        }
    }
}