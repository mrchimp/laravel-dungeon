<?php

namespace Dungeon\Actions\Entities;

use Dungeon\Actions\Action;
use Dungeon\Entity;
use Dungeon\Exceptions\EntityNotPossessedException;
use Dungeon\Exceptions\MissingEntityException;
use Dungeon\User;

class Drop extends Action
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
     *
     * @throws MissingEntityException
     */
    public function perform()
    {
        if (!$this->entity) {
            throw new MissingEntityException;
        }

        if (!$this->entity->ownedBy($this->user)) {
            throw new EntityNotPossessedException;
        }

        $this->entity->moveToRoom($this->user->getRoom());
        $this->entity->save();
    }
}
