<?php

namespace App\Dungeon\Entities;

use App\User;

class Finder
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function find($query)
    {
        $entity = $this->findInInventory($query);

        if (!$entity) {
            return null;
        }

        return $entity;
    }

    protected function findInInventory($query)
    {
        return $this->user->inventory->first(function ($entity) use ($query) {
            return $entity->nameMatchesQuery($query);
        });
    }
}