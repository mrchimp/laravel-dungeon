<?php

namespace Dungeon\Actions\Entities;

use Dungeon\Actions\Action;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\Exceptions\UntakeableEntityException;
use Dungeon\Exceptions\UserIsDeadException;
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

    /**
     * @var Entity
     */
    protected $container;

    public function __construct(User $user, ?Entity $entity, ?Entity $container)
    {
        $this->user = $user;
        $this->entity = $entity;
        $this->container = $container;
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

        if (!$this->user->body) {
            throw new UserIsDeadException;
        }

        $this->entity->moveToContainer($this->user->body);
        $this->entity->save();
    }
}
