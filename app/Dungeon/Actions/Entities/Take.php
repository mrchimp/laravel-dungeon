<?php

namespace Dungeon\Actions\Entities;

use Dungeon\Actions\Action;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\UntakeableEntityException;
use Dungeon\User;

class Take extends Action
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var Entity
     */
    protected $entity;

    public function __construct(User $user, ?Entity $entity)
    {
        $this->user = $user;
        $this->entity = $entity;
    }

    /**
     * Perform the action
     */
    public function perform()
    {
        if (!$this->entity) {
            throw new MissingEntityException;
        }

        if ($this->entity->ownedBy($this->user)) {
            throw new EntityPossessedException;
        }

        if (!$this->entity->canBeTaken()) {
            throw new UntakeableEntityException;
        }

        $this->entity->giveToUser($this->user);
        $this->entity->save();
    }
}
